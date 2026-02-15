<?php

namespace App\Services\VpnServer;

use App\Libraries\SshClient;
use App\Models\AccountModel;
use App\Models\ServerModel;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use RuntimeException;

class XuiService
{
    private SshClient $ssh;
    private array $server;
    private int $serverId;
    private string $host;
    private int $port;
    private string $tunnelDomain;
    private string $storageDir;

    public function __construct(int $serverId)
    {
        $serverModel = new ServerModel();
        $this->server = $serverModel->find($serverId);

        if (!$this->server) {
            throw new RuntimeException("Server with ID {$serverId} not found");
        }

        if ($this->server['vpn_type'] !== 'xui') {
            throw new RuntimeException("Server {$serverId} is not an XUI server");
        }

        $this->ssh = new SshClient();
        $this->serverId = $serverId;
        $this->host = $this->server['ip_address'];
        $this->port = $this->server['vpn_port'];
        $this->tunnelDomain = $this->server['tunnel_domain'];

        // Storage directory per server
        $this->storageDir = WRITEPATH . "storage/xui/{$serverId}/";

        if (!is_dir($this->storageDir)) {
            mkdir($this->storageDir, 0755, true);
        }
    }

    public function addUser(string $clientName, int $initTraffic): array
    {
        $cmd = 'bash /root/scripts/add-xui.sh';
        $cmd .= ' ' . escapeshellarg($clientName);
        $cmd .= ' ' . escapeshellarg($initTraffic);

        $output = $this->ssh->runCommand($this->host, $cmd);

        if (preg_match('/__RESULT__=(\{.*\})/', $output, $m)) {
            $result = json_decode($m[1], true);
        } else {
            return [
                'status' => 'error',
                'error'   => 'INVALID_SCRIPT_OUTPUT',
                'raw'     => $output,
            ];
        }

        if (($result['status'] ?? '') === 'ok') {
            $uuid = $result['uuid'] ?? null;
//            $configLink = "vless://{$uuid}@{$this->tunnelDomain}:{$this->port}?type=tcp&security=none#{$clientName}";
            $configLink = $result['config'];
            // Generate QR Code
            if ($uuid) {
                try {
                    $qrResult = $this->generateQrCode($clientName, $configLink);
                    if (($qrResult['qr_status'] ?? '') === 'ok') {
                        $result['qr_status'] = 'ok';
                        $result['download_url'] = base_url("api/xui/download/{$this->serverId}/{$clientName}");
                    } else {
                        $result['qr_status'] = 'failed';
                        $result['qr_error']  = $qrResult['qr_error'] ?? 'UNKNOWN_ERROR';
                    }
                } catch (\Throwable $e) {
                    $result['qr_status'] = 'failed';
                    $result['qr_error']  = $e->getMessage();
                }
            } else {
                $result['qr_status'] = 'skipped';
                $result['qr_error']  = 'NO_UUID';
            }

            // Save to database
            $accountModel = new AccountModel();

            $metadata = json_encode([
                'protocol' => 'vless',
                'uuid' => $uuid,
                'inbound_id' => $result['inbound_id'] ?? null,
                'config' => $configLink
            ]);

            $data = [
                'server_id' => $this->serverId,
                'client_name' => $clientName,
                'file_name' => $clientName . '.png',
                'traffic_total_bytes' => $initTraffic * 1024 * 1024 * 1024,
                'traffic_used_bytes' => 0,
                'metadata' => $metadata,
                'status' => 1
            ];

            $existing = $accountModel->getByClientAndServer($clientName, $this->serverId);

            if ($existing) {
                $accountModel->update($existing['id'], $data);
            } else {
                $accountModel->insert($data);
            }
        }

        return $result;
    }

    public function deleteUser(string $client): array
    {
        $cmd = "bash /root/scripts/delete-xui.sh " . escapeshellarg($client);
        $output = $this->ssh->runCommand($this->host, $cmd);

        if (preg_match('/__RESULT__=(\{.*\})/', $output, $m)) {
            $result = json_decode($m[1], true);

            if (isset($result['status']) && $result['status'] === 'ok') {
                // Delete QR code file
                $qrFilePath = $this->storageDir . $client . '.png';
                if (file_exists($qrFilePath)) {
                    unlink($qrFilePath);
                }

                // Database cleanup
                $accountModel = new AccountModel();
                $existing = $accountModel->getByClientAndServer($client, $this->serverId);

                if ($existing) {
                    $accountModel->delete($existing['id']);
                    $result['db_status'] = 'deleted';
                } else {
                    $result['db_status'] = 'not_found';
                }
            }

            return $result;
        }

        return [
            'status' => 'error',
            'error'  => 'INVALID_SCRIPT_OUTPUT',
            'raw'    => $output,
        ];
    }

    public function updateUser(string $client, string $mode, $value = null): array
    {
        $cmd = "bash /root/scripts/update-xui.sh " . escapeshellarg($client) . ' ' . escapeshellarg($mode);
        if ($value !== null) {
            $cmd .= ' ' . escapeshellarg((string)$value);
        }

        $output = $this->ssh->runCommand($this->host, $cmd);

        if (preg_match('/__RESULT__=(\{.*\})/', $output, $m)) {
            return json_decode($m[1], true);
        }

        return [
            'status' => 'error',
            'error'  => 'INVALID_SCRIPT_OUTPUT',
            'raw'    => $output,
        ];
    }

    public function listUsers(bool $activeOnly = false, bool $inactiveOnly = false): array
    {
        $cmd = 'bash /root/scripts/list-xui.sh';
        if ($activeOnly) $cmd .= ' --active';
        if ($inactiveOnly) $cmd .= ' --inactive';

        $output = $this->ssh->runCommand($this->host, $cmd);

        // Parse shell script output
        if (!preg_match('/__RESULT__=(.*)/s', $output, $m)) {
            return ['status' => 'error', 'error' => 'INVALID_SSH_OUTPUT', 'raw' => $output];
        }

        $rawJson = trim($m[1]);
        $serverClients = json_decode($rawJson, true);

        // Check if JSON parsing failed
        if (!is_array($serverClients)) {
            return [
                'status' => 'error',
                'error'  => 'JSON_PARSE_FAILED',
                'debug_raw_json' => $rawJson,
                'json_error' => json_last_error_msg()
            ];
        }

        $accountModel = new AccountModel();
        $processed = [];

        foreach ($serverClients as $client) {
            $clientName = $client['client'] ?? null;
            $uuid = $client['uuid'] ?? null;

            if (!$clientName || !$uuid) {
                continue;
            }

            // Build config link with remark
            $remark = $client['remark'] ?? 'default';
            $configLink = "vless://{$uuid}@{$this->tunnelDomain}:{$this->port}?type=tcp&security=none#{$remark}-{$clientName}";

            // Prepare metadata
            $metadata = json_encode([
                'protocol' => 'vless',
                'uuid' => $uuid,
                'inbound_id' => null,
                'config' => $configLink
            ]);

            // Prepare data for database
            $data = [
                'server_id'           => $this->serverId,
                'client_name'         => $clientName,
                'file_name'           => $clientName . '.png',
                'traffic_total_bytes' => $client['total'] ?? 0,
                'traffic_used_bytes'  => 0,
                'metadata'            => $metadata,
                'status'              => ($client['enable_val'] ?? 1) == 1 ? 1 : 0
            ];

            // Insert or Update
            $existing = $accountModel->getByClientAndServer($clientName, $this->serverId);

            if ($existing) {
                $accountModel->update($existing['id'], $data);
            } else {
                $accountModel->insert($data);
            }

            // Generate QR Code if not exists
            $qrPath = $this->storageDir . $clientName . '.png';
            if (!file_exists($qrPath)) {
                $this->generateQrCode($clientName, $configLink);
            }

            $processed[] = [
                'client' => $clientName,
                'download_url' => base_url("api/xui/download/{$this->serverId}/{$clientName}")
            ];
        }

        return [
            'status' => 'ok',
            'server_id' => $this->serverId,
            'synced_count' => count($processed),
            'clients' => $processed
        ];
    }

    public function generateQrCode(string $client, string $config): array
    {
        $qrResult = [
            'qr_status' => 'failed',
            'qr_error'  => null,
            'qr_path'   => null,
        ];

        $qrDir = $this->storageDir;
        if (!is_dir($qrDir)) {
            mkdir($qrDir, 0755, true);
        }

        $qrFile = $qrDir . $client . '.png';

        try {
            $builder = new Builder(
                writer: new PngWriter(),
                writerOptions: [],
                validateResult: false,
                data: $config,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::High,
                size: 300,
                margin: 10,
                roundBlockSizeMode: RoundBlockSizeMode::Margin,
                labelText: $client,
                labelFont: new OpenSans(20),
                labelAlignment: LabelAlignment::Center
            );

            $result = $builder->build();
            $result->saveToFile($qrFile);

            $qrResult['qr_status'] = 'ok';
            $qrResult['qr_path']   = $qrFile;

        } catch (\Throwable $e) {
            $qrResult['qr_error'] = $e->getMessage();
        }

        return $qrResult;
    }

    /**
     * Get file path for download
     */
    public function getFilePath(string $clientName): ?string
    {
        $filePath = $this->storageDir . $clientName . '.png';
        return file_exists($filePath) ? $filePath : null;
    }
}