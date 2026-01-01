<?php

namespace App\Services\VpnServer;

use App\Libraries\SshClient;
use RuntimeException;

class OpenVpnService
{
    private SshClient $ssh;
    private string $host;
    private string $remoteOvpnDir = '/root';
    private string $localOvpnDir;

    private string $tunnleHost;

    public function __construct(string $host = '5.161.144.182')
    {
        $this->ssh = new SshClient();
        $this->host = $host;
        $this->localOvpnDir = WRITEPATH . 'storage/openvpn/';
        $this->tunnleHost = 'ov.kouchnet.site';

        if (!is_dir($this->localOvpnDir)) {
            mkdir($this->localOvpnDir, 0755, true);
        }
    }

    /**
     * Add a VPN client
     */
    public function addClient(string $clientName): array
    {
        $output = $this->ssh->runCommand($this->host, "bash /root/scripts/add-openvpn.sh " . escapeshellarg($clientName));

        if (preg_match('/__RESULT__=(\{.*\})/', $output, $m)) {
            return json_decode($m[1], true);
        }

        return [
            'status' => 'error',
            'error' => 'INVALID_SCRIPT_OUTPUT',
            'client' => $clientName,
        ];
    }

    /**
     * Delete (revoke) a VPN client
     */
    public function deleteClient(string $clientName): array
    {
        $result = $this->ssh->runCommand($this->host, "bash /root/scripts/delete-openvpn.sh " . escapeshellarg($clientName));

        if (preg_match('/__RESULT__=(\{.*\})/', $result, $m)) {
            $resultArray = json_decode($m[1], true);
        } else {
            $resultArray = [
                'status' => 'error',
                'error' => 'INVALID_SCRIPT_OUTPUT',
                'client' => $clientName,
            ];
        }

        $localFile = $this->localOvpnDir . $clientName . '.ovpn';
        $resultArray['local_deleted'] = false;

        if (file_exists($localFile)) {
            unlink($localFile);
            $resultArray['local_deleted'] = true;
        }


        return $resultArray;
    }


    /**
     * Download the .ovpn file after creation
     */
    public function downloadConfig(string $clientName): array
    {
        $remoteFile = "{$this->remoteOvpnDir}/{$clientName}.ovpn";
        $localFile  = "{$this->localOvpnDir}{$clientName}.ovpn";

        try {
            $this->ssh->downloadFile($this->host, $remoteFile, $localFile);
            $this->rewriteRemote($localFile, $this->tunnleHost);
        } catch (\RuntimeException $e) {
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
            throw new RuntimeException("OVPN file not found: $filePath");
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

}
