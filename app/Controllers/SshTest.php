<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class SshTest extends Controller
{
    public function index()
    {
        $host = '5.161.144.182';
        $user = 'root';
        $key = '/root/.ssh/private_hetzner2.key';
        $command = '/root/scripts/test.sh';

        $conn = ssh2_connect($host, 22);
        if (!$conn) {
            return "SSH connection failed";
        }

        ssh2_auth_pubkey_file(
            $conn,
            $user,
            $key . '.pub',
            $key
        );

        $stream = ssh2_exec($conn, $command);
        stream_set_blocking($stream, true);
        $output = stream_get_contents($stream);
        fclose($stream);

        return nl2br($output);
    }
}
