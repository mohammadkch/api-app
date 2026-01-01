<?php

namespace App\Services\VpnServer;

use App\Libraries\SshClient;
use Endroid\QrCode\QrCode;
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

    public function addUser(string $clientName = null): array
    {
        $cmd = 'bash /root/scripts/add-xui.sh';
        if ($clientName) {
            $cmd .= ' ' . escapeshellarg($clientName);
        }

        $output = $this->ssh->runCommand($this->host, $cmd);

        if (preg_match('/__RESULT__=(\{.*\})/', $output, $m)) {
            $result = json_decode($m[1], true);
        } else {
            return [
                'status' => 'error',
                'error' => 'INVALID_SCRIPT_OUTPUT',
                'raw'   => $output,
            ];
        }

        if (($result['status'] ?? '') === 'ok') {
            $link = $result['config'] ?? null;
            if ($link) {
                try {
                    $qrResult = $this->generateQrCode($clientName, $link);
                    if (($qrResult['status'] ?? '') === 'ok') {
                        $result['qr_status'] = 'ok';
                        $result['qr_path']   = $qrResult['path'];
                    } else {
                        $result['qr_status'] = 'failed';
                        $result['qr_error']  = $qrResult['message'] ?? $qrResult['error'] ?? 'UNKNOWN_ERROR';
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
                'error' => 'INVALID_OUTPUT',
                'raw'   => $output
            ];
        }

        return [
            'status' => 'error',
            'error' => 'NO_RESULT',
            'raw'   => $output
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
            return json_decode($m[1], true);
        }

        return [
            'status' => 'error',
            'error' => 'INVALID_SCRIPT_OUTPUT',
            'raw'   => $output,
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

    public function generateQrCode(string $client, string $link): array
    {
        try {
            $qr = new QrCode($link); // <- use constructor, not create()
            $qr->setSize(300);
            $qr->setMargin(10);

            $writer = new PngWriter();
            $result = $writer->write($qr);

            $filePath = $this->storageDir . $client . '.png';
            $result->saveToFile($filePath);

            return [
                'status' => 'ok',
                'client' => $client,
                'path'   => $filePath
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 'error',
                'client' => $client,
                'error'  => $e->getMessage()
            ];
        }
    }

}
