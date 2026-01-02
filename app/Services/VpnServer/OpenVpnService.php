<?php

namespace App\Services\VpnServer;

use App\Libraries\SshClient;
use App\Models\AccountModel;
use RuntimeException;

class OpenVpnService
{
    private SshClient $ssh;
    private string $host;
    private string $remoteOvpnDir = '/root';
    private string $localOvpnDir;
    private string $tunnelHost;

    public function __construct(string $host = '5.161.144.182')
    {
        $this->ssh = new SshClient();
        $this->host = $host;
        $this->localOvpnDir = WRITEPATH . 'storage/openvpn/';
        $this->tunnelHost = 'ov.kouchnet.site';

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
                $accountModel = new AccountModel();

                $data = [
                    'server_id'   => 1,
                    'client_name' => $clientName,
                    'protocol'    => 'ovpn',
                    'status'      => 1,
                    'config_link' => $this->tunnelHost
                ];

                $existing = $accountModel->where('client_name', $clientName)
                    ->where('protocol', 'ovpn')
                    ->first();
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
            $accountModel->where('client_name', $clientName)
                ->where('protocol', 'ovpn')
                ->delete();
        }

        return $resultArray;
    }

    public function downloadConfig(string $clientName): array
    {
        $remoteFile = "{$this->remoteOvpnDir}/{$clientName}.ovpn";
        $localFile  = "{$this->localOvpnDir}{$clientName}.ovpn";

        try {
            $this->ssh->downloadFile($this->host, $remoteFile, $localFile);
            $this->rewriteRemote($localFile, $this->tunnelHost);
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
                // 1. Download and rewrite the .ovpn file
                $download = $this->downloadConfig($clientName);

                // 2. Check and update Database
                $existing = $accountModel->where('client_name', $clientName)
                    ->where('protocol', 'ovpn')
                    ->first();

                $data = [
                    'server_id'   => 1,
                    'client_name' => $clientName,
                    'protocol'    => 'ovpn',
                    'status'      => 1,
                    'config_link' => $this->tunnelHost
                ];

                if ($existing) {
                    $accountModel->update($existing['id'], $data);
                } else {
                    $accountModel->insert($data);
                }

                $processed[] = [
                    'client' => $clientName,
                    'file_status' => $download['status'] ?? 'error'
                ];
            }

            return [
                'status' => 'ok',
                'total' => count($serverClients),
                'processed_clients' => $processed
            ];
        }

        return [
            'status' => 'error',
            'error'  => 'INVALID_SCRIPT_OUTPUT'
        ];
    }
}