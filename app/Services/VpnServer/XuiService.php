<?php

namespace App\Services\VpnServer;

use App\Libraries\SshClient;

class XuiService
{
    private SshClient $ssh;
    private string $host;

    public function __construct(string $host)
    {
        $this->ssh  = new SshClient();
        $this->host = $host;
    }

    private function parseResult(string $output): array
    {
        if (preg_match('/__RESULT__=(\{.*\})/s', $output, $m)) {
            $result = json_decode($m[1], true);
            if (is_array($result)) {
                return $result;
            }
        }

        // fallback امن
        return [
            'status' => 'error',
            'error'  => 'INVALID_SCRIPT_OUTPUT',
            'raw'    => $output,
        ];
    }

    public function addUser(?string $client): array
    {
        $cmd = 'bash /root/scripts/add-xui.sh';
        if ($client) {
            $cmd .= ' ' . escapeshellarg($client);
        }

        return $this->parseResult(
            $this->ssh->runCommand($this->host, $cmd)
        );
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

}
