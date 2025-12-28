<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class SshTest extends Controller
{
    public function index()
    {
//        $host = '5.161.144.182';
//        $user = 'root';
//        $key = '/var/www/.ssh/cross-servers.key';
//        $command = '/root/scripts/test.sh';
//
//        $conn = ssh2_connect($host, 22);
//        if (!$conn) {
//            return "SSH connection failed";
//        }
//
//        ssh2_auth_pubkey_file(
//            $conn,
//            $user,
//            $key . '.pub',
//            $key
//        );
//
//        $stream = ssh2_exec($conn, $command);
//        stream_set_blocking($stream, true);
//        $output = stream_get_contents($stream);
//        fclose($stream);
//
//        return nl2br($output);

        $host = '5.161.144.182';
        $user = 'root';
        $priv = '/var/www/.ssh/cross-servers.key';
        $pub  = '/var/www/.ssh/cross-servers.key.pub';

        $conn = ssh2_connect($host, 22);
        if (!$conn) {
            die('connect failed');
        }

        if (!ssh2_auth_pubkey_file($conn, $user, $pub, $priv)) {
            die('auth failed');
        }

        $stream = ssh2_exec($conn, 'bash /root/scripts/test.sh');
        stream_set_blocking($stream, true);
        echo stream_get_contents($stream);

        $client = 'ali';
        $cmd = escapeshellcmd("/root/scripts/ovpn-add.sh $client");
        $output = shell_exec($cmd);
        echo $output;


    }

    public function addOpen()
    {
        $host = '5.161.144.182';
        $user = 'root';
        $priv = '/var/www/.ssh/cross-servers.key';
        $pub  = '/var/www/.ssh/cross-servers.key.pub';

        $conn = ssh2_connect($host, 22);
        if (!$conn) {
            die('connect failed');
        }

        if (!ssh2_auth_pubkey_file($conn, $user, $pub, $priv)) {
            die('auth failed');
        }

        $client = 'ali';
        $command = "bash /root/scripts/ovpn-add.sh $client";

        $stream = ssh2_exec($conn, $command);
        stream_set_blocking($stream, true);
        $output = stream_get_contents($stream);
        fclose($stream);

        echo $output;
    }
}
