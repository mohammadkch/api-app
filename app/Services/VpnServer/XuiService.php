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

    /**
     * Add x-ui user
     */
    public function addUser(string $clientName = null): array
    {
        $cmd = 'bash /root/scripts/add-xui.sh';
        if ($clientName) {
            $cmd .= ' ' . escapeshellarg($clientName);
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

}
