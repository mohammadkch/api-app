<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\SshClient;

class SshTest extends Controller
{
    private SshClient $ssh;
    private string $host = '5.161.144.182';

    public function __construct()
    {
        $this->ssh = new SshClient();
    }

    public function index()
    {
        try {
            $output = $this->ssh->runCommand($this->host, 'bash /root/scripts/test.sh');
            echo $output;
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function addOpenVpn(): void
    {
        $client = 'ali-n2';
        $command = "bash /root/scripts/add-openvpn.sh " . escapeshellarg($client);

        try {
            $output = $this->ssh->runCommand($this->host, $command);
            echo $output;
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function deleteOpenVpn(): void
    {
        $client = 'ali-n';
        $command = "bash /root/scripts/delete-openvpn.sh " . escapeshellarg($client);

        try {
            $output = $this->ssh->runCommand($this->host, $command);
            echo "<pre>$output</pre>";
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}