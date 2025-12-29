<?php

namespace App\Services\VpnServer;

use App\Libraries\SshClient;

class OpenVpnManager
{
    private SshClient $ssh;
    private string $host;

    public function __construct(string $host = '5.161.144.182')
    {
        $this->ssh  = new SshClient();
        $this->host = $host;
    }

    /**
     * Add a VPN client
     *
     * @param string $clientName
     * @return string JSON output from add-openvpn.sh
     */
    public function addClient(string $clientName): string
    {
        $cmd = "bash /root/scripts/add-openvpn.sh " . escapeshellarg($clientName);
        return $this->ssh->runCommand($this->host, $cmd);
    }

    /**
     * Delete (revoke) a VPN client
     *
     * @param string $clientName
     * @return string JSON output from delete-openvpn.sh
     */
    public function deleteClient(string $clientName): string
    {
        $cmd = "bash /root/scripts/delete-openvpn.sh " . escapeshellarg($clientName);
        return $this->ssh->runCommand($this->host, $cmd);
    }
}
