<?php

namespace App\Services\VpnServer;

use App\Libraries\SshClient;
use App\Models\AccountModel;
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
    private string $host;
    private string $storageDir;

    public function __construct(string $host)
    {
        $this->ssh = new SshClient();
        $this->host = $host;
        $this->storageDir = WRITEPATH . 'storage/xui/';

        if (!is_dir($this->storageDir)) {
            mkdir($this->storageDir, 0755, true);
        }
    }

    public function addUser(string $clientName = null, int $initTraffic = 0): array
    {
        $cmd = 'bash /root/scripts/add-xui.sh';
        if ($clientName) {
            $cmd .= ' ' . escapeshellarg($clientName);
            $cmd .= ' ' . escapeshellarg($initTraffic);
        }

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
            $link = $result['config'] ?? null;
            if ($link) {
                try {
                    $qrResult = $this->generateQrCode($clientName, $link);
                    if (($qrResult['qr_status'] ?? '') === 'ok') {
                        $result['qr_status'] = 'ok';
                        $result['qr_path']   = $qrResult['qr_path'];
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
                $result['qr_error']  = 'NO_CONFIG_LINK';
            }
        }

        if (($result['status'] ?? '') === 'ok') {
            $accountModel = new AccountModel();

            $data = [
                'server_id' => 1,
                'client_name' => $result['client'],
                'protocol' => 'vless',
                'uuid' => $result['uuid'] ?? null,
                'traffic_limit_gb' => $initTraffic,
                'traffic_total_bytes' => ($initTraffic * 1024 * 1024 * 1024),
                'config_link' => $result['config'] ?? null,
                'status' => 1
            ];

            $existing = $accountModel->where('client_name', $result['client'])->first();
            if ($existing) {
                $accountModel->update($existing['id'], $data);
            } else {
                $accountModel->insert($data);
            }
        }

        return $result;
    }

    public function listUsers(bool $inactive = false): array
    {
        $cmd = 'bash /root/scripts/list-xui.sh';
        if ($inactive) {
            $cmd .= ' --inactive';
        }

        $output = $this->ssh->runCommand($this->host, $cmd);

        if (preg_match('/__RESULT__=(\{.*\})/', $output, $m)) {
            return json_decode($m[1], true) ?? [
                'status' => 'error',
                'error'  => 'INVALID_OUTPUT',
                'raw'    => $output
            ];
        }

        return [
            'status' => 'error',
            'error'  => 'NO_RESULT',
            'raw'    => $output
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

    public function deleteUser(string $client): array
    {
        $cmd = "bash /root/scripts/delete-xui.sh " . escapeshellarg($client);

        $output = $this->ssh->runCommand($this->host, $cmd);

        if (preg_match('/__RESULT__=(\{.*\})/', $output, $m)) {

            $result = json_decode($m[1], true);

            if (isset($result['status']) && $result['status'] === 'ok') {

                $qrFilePath = WRITEPATH . "storage/xui/{$client}.png";

                if (file_exists($qrFilePath)) {
                    unlink($qrFilePath);
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

    public function getClientInfo(string $client): array
    {
        $users = $this->listUsers(true);

        if (($users['status'] ?? '') !== 'ok') {
            return $users;
        }

        foreach ($users['clients'] ?? [] as $u) {
            if (($u['email'] ?? '') === $client) {
                return [
                    'status' => 'ok',
                    'client' => $client,
                    'config' => $u['config'] ?? null
                ];
            }
        }

        return [
            'status' => 'error',
            'error' => 'CLIENT_NOT_FOUND',
            'client' => $client
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
                labelAlignment: LabelAlignment::Center,
//                logoPath: null,
//                logoResizeToWidth: null,
//                logoPunchoutBackground: true
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


}
