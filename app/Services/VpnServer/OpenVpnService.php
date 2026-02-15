<?php

namespace App\Services\VpnServer;

use App\Libraries\SshClient;
use App\Models\AccountModel;
use App\Models\ServerModel;
use RuntimeException;

class OpenVpnService
{
    private SshClient $ssh;
    private array $server;
    private int $serverId;
    private string $host;
    private string $tunnelDomain;
    private string $remoteOvpnDir = '/root';
    private string $localOvpnDir;

    public function __construct(int $serverId)
    {
        $serverModel = new ServerModel();
        $this->server = $serverModel->find($serverId);

        if (!$this->server) {
            throw new RuntimeException("Server with ID {$serverId} not found");
        }

        if ($this->server['vpn_type'] !== 'openvpn') {
            throw new RuntimeException("Server {$serverId} is not an OpenVPN server");
        }

        $this->ssh = new SshClient();
        $this->serverId = $serverId;
        $this->host = $this->server['ip_address'];
        $this->tunnelDomain = $this->server['tunnel_domain'];

        // Storage directory per server
        $this->localOvpnDir = WRITEPATH . "storage/openvpn/{$serverId}/";

        if (!is_dir($this->localOvpnDir)) {
            mkdir($this->localOvpnDir, 0755, true);
        }
    }

    public function addClient(string $clientName): array
    {
        $output = $this->ssh->runCommand($this->host, "bash /root/scripts/add-openvpn.sh " . escapeshellarg($clientName));

        if (preg_match('/__RESULT__=(\{.*\})/', $output, $m)) {
            $result = json_decode($m[1], true);

            if (isset($result['status']) && $result['status'] === 'ok') {
                // Download the config file
                $download = $this->downloadConfig($clientName);

                if ($download['status'] === 'ok') {
                    $result['download_url'] = base_url("api/openvpn/download/{$this->serverId}/{$clientName}");
                }

                // Save to database
                $accountModel = new AccountModel();

                $metadata = json_encode([
                    'protocol' => 'ovpn'
                ]);

                $data = [
                    'server_id'   => $this->serverId,
                    'client_name' => $clientName,
                    'file_name'   => $clientName . '.ovpn',
                    'metadata'    => $metadata,
                    'status'      => 1
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

        return [
            'status' => 'error',
            'error' => 'INVALID_SCRIPT_OUTPUT',
            'client' => $clientName,
        ];
    }

    public function deleteClient(string $clientName): array
    {
        $output = $this->ssh->runCommand($this->host, "bash /root/scripts/delete-openvpn.sh " . escapeshellarg($clientName));

        if (preg_match('/__RESULT__=(\{.*\})/', $output, $m)) {
            $resultArray = json_decode($m[1], true);
        } else {
            $resultArray = [
                'status' => 'error',
                'error' => 'INVALID_SCRIPT_OUTPUT',
                'client' => $clientName,
            ];
        }

        if (isset($resultArray['status']) && $resultArray['status'] === 'ok') {
            // Local file cleanup
            $localFile = $this->localOvpnDir . $clientName . '.ovpn';
            if (file_exists($localFile)) {
                unlink($localFile);
            }

            // Database cleanup
            $accountModel = new AccountModel();
            $existing = $accountModel->getByClientAndServer($clientName, $this->serverId);

            if ($existing) {
                $accountModel->delete($existing['id']);
                $resultArray['db_status'] = 'deleted';
            } else {
                $resultArray['db_status'] = 'not_found';
            }
        }

        return $resultArray;
    }

    public function downloadConfig(string $clientName): array
    {
        $remoteFile = "{$this->remoteOvpnDir}/{$clientName}.ovpn";
        $localFile  = "{$this->localOvpnDir}{$clientName}.ovpn";

        try {
            $this->ssh->downloadFile($this->host, $remoteFile, $localFile);
            $this->rewriteRemote($localFile, $this->tunnelDomain);
        } catch (RuntimeException $e) {
            return [
                'status' => 'error',
                'error' => 'DOWNLOAD_FAILED',
                'client' => $clientName,
                'message' => $e->getMessage(),
            ];
        }

        return [
            'status' => 'ok',
            'client' => $clientName,
            'path' => $localFile,
        ];
    }

    private function rewriteRemote(string $filePath, string $newHost): void
    {
        if (!file_exists($filePath)) {
            throw new RuntimeException(".ovpn file not found: $filePath");
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES);
        foreach ($lines as &$line) {
            if (preg_match('/^remote\s+[\S]+\s+(\d+)$/', $line, $m)) {
                $port = $m[1];
                $line = "remote $newHost $port";
                break;
            }
        }

        file_put_contents($filePath, implode("\n", $lines));
    }

    public function listClients(): array
    {
        $output = $this->ssh->runCommand($this->host, "bash /root/scripts/list-open.sh");

        if (preg_match('/__RESULT__=(\{.*\})/', $output, $m)) {
            $result = json_decode($m[1], true);
            $serverClients = $result['clients'] ?? [];

            $accountModel = new AccountModel();
            $processed = [];

            foreach ($serverClients as $clientName) {
                // Download and rewrite the .ovpn file
                $download = $this->downloadConfig($clientName);

                // Prepare metadata
                $metadata = json_encode([
                    'protocol' => 'ovpn'
                ]);

                // Check and update Database
                $existing = $accountModel->getByClientAndServer($clientName, $this->serverId);

                $data = [
                    'server_id'   => $this->serverId,
                    'client_name' => $clientName,
                    'file_name'   => $clientName . '.ovpn',
                    'metadata'    => $metadata,
                    'status'      => 1
                ];

                if ($existing) {
                    $accountModel->update($existing['id'], $data);
                } else {
                    $accountModel->insert($data);
                }

                $processed[] = [
                    'client' => $clientName,
                    'file_status' => $download['status'] ?? 'error',
                    'download_url' => base_url("api/openvpn/download/{$this->serverId}/{$clientName}")
                ];
            }

            return [
                'status' => 'ok',
                'server_id' => $this->serverId,
                'total' => count($serverClients),
                'processed_clients' => $processed
            ];
        }

        return [
            'status' => 'error',
            'error'  => 'INVALID_SCRIPT_OUTPUT'
        ];
    }

    /**
     * Get file path for download
     */
    public function getFilePath(string $clientName): ?string
    {
        $filePath = $this->localOvpnDir . $clientName . '.ovpn';
        return file_exists($filePath) ? $filePath : null;
    }
}