<?php

namespace App\Services\VpnServer;

use App\Libraries\SshClient;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class XuiService
{
    private SshClient $ssh;
    private string $host;
    private string $qrPath;

    public function __construct(string $host)
    {
        $this->ssh = new SshClient();
        $this->host = $host;
        $this->qrPath = WRITEPATH . 'storage/xui/';
        if (!is_dir($this->qrPath)) {
            mkdir($this->qrPath, 0755, true);
        }
    }

    private function parseResult(string $output): array
    {
        if (preg_match('/__RESULT__=(\{.*\})/s', $output, $m)) {
            $result = json_decode($m[1], true);
            if (is_array($result)) {
                return $result;
            }
        }

        return [
            'status' => 'error',
            'error'  => 'INVALID_SCRIPT_OUTPUT',
            'raw'    => $output,
        ];
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

            if (($result['status'] ?? '') === 'ok') {
                $qrFile = $this->generateQr($result['config'], $clientName);
                $result['qr_path'] = $qrFile;
            }

            return $result;
        }

        return [
            'status' => 'error',
            'error' => 'INVALID_SCRIPT_OUTPUT',
            'raw'   => $output,
        ];
    }

    public function updateUser(string $client, string $mode, ?string $value = null): array
    {
        $cmd = "bash /root/scripts/update-xui.sh "
            . escapeshellarg($client) . " "
            . escapeshellarg($mode);

        if ($value !== null) {
            $cmd .= ' ' . escapeshellarg($value);
        }

        return $this->parseResult(
            $this->ssh->runCommand($this->host, $cmd)
        );
    }

    public function deleteUser(string $client): array
    {
        $cmd = 'bash /root/scripts/delete-xui.sh ' . escapeshellarg($client);

        return $this->parseResult(
            $this->ssh->runCommand($this->host, $cmd)
        );
    }

    public function listUsers(bool $inactive = false): array
    {
        $cmd = 'bash /root/scripts/list-xui.sh';

        if ($inactive) {
            $cmd .= ' --inactive';
        }

        $output = $this->ssh->runCommand($this->host, $cmd);

        if (preg_match('/__RESULT__=(\{.*\})/s', $output, $m)) {
            return json_decode($m[1], true);
        }

        return [
            'status' => 'error',
            'error'  => 'INVALID_SCRIPT_OUTPUT',
            'raw'    => $output,
        ];
    }

    public function generateQrCode(string $client, string $link): array
    {
        $dir = WRITEPATH . 'storage/xui/';
        $file = $dir . $client . '.png';

        try {
            if (!is_dir($dir)) {
                mkdir($dir, 0770, true);
                chown($dir, 'www-data');
                chgrp($dir, 'www-data');
            }

            $qrCode = new QrCode($link);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            $result->saveToFile($file);

            return [
                'status' => 'ok',
                'client' => $client,
                'path' => $file
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 'error',
                'error' => 'QR_GENERATION_FAILED',
                'message' => $e->getMessage()
            ];
        }
    }

    public function getClientInfo(string $clientName): array
    {
        $cmd = "bash /root/scripts/list-xui.sh --inactive";
        $output = $this->ssh->runCommand($this->host, $cmd);

        if (preg_match('/__RESULT__=(\{.*\})/', $output, $m)) {
            $data = json_decode($m[1], true);
            if (!empty($data['clients'][$clientName]['config'])) {
                $data['clients'][$clientName]['qr_path'] = $this->generateQr(
                    $data['clients'][$clientName]['config'],
                    $clientName
                );
            }
            return $data['clients'][$clientName] ?? [];
        }

        return [
            'status' => 'error',
            'error'  => 'INVALID_SCRIPT_OUTPUT',
            'raw'    => $output,
        ];
    }

}
