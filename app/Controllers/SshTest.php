<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class SshTest extends Controller
{

    private string $private_key = '/var/www/.ssh/cross-servers.key';
    private string $public_key = '/var/www/.ssh/cross-servers.key.pub';


    public function index()
    {


        $host = '5.161.144.182';
        $user = 'root';


        $conn = ssh2_connect($host, 22);
        if (!$conn) {
            die('connect failed');
        }

        if (!ssh2_auth_pubkey_file($conn, $user, $this->public_key, $this->private_key)) {
            die('auth failed');
        }

        $stream = ssh2_exec($conn, 'bash /root/scripts/test.sh');
        stream_set_blocking($stream, true);
        echo stream_get_contents($stream);

    }

    public function addOpenVpn() : void
    {
        $host = '5.161.144.182';
        $user = 'root';

        $conn = ssh2_connect($host, 22);
        if (!$conn) {
            die('connect failed');
        }

        if (!ssh2_auth_pubkey_file($conn, $user, $this->public_key, $this->private_key)) {
            die('auth failed');
        }

        $client = 'ali-n';
        $command = "bash /root/scripts/add-openvpn.sh $client";

        $stream = ssh2_exec($conn, $command);
        stream_set_blocking($stream, true);
        $output = stream_get_contents($stream);
        fclose($stream);

        echo $output;
    }

    public function deleteOpenVpn() : void
    {
        $host = '5.161.144.182';
        $user = 'root';

        $conn = ssh2_connect($host, 22);
        if (!$conn) {
            die('connect failed');
        }

        if (!ssh2_auth_pubkey_file($conn, $user, $this->public_key, $this->private_key)) {
            die('auth failed');
        }

        $client = 'ali';
        $command = "bash /root/scripts/add-openvpn.sh $client";
        $delete_command = "bash /root/scripts/delete-openvpn.sh " . escapeshellarg($client);

        $stream = ssh2_exec($conn, $delete_command);
        stream_set_blocking($stream, true);
        $output = stream_get_contents($stream);
        fclose($stream);

        echo "<pre>$output</pre>";
    }
}
