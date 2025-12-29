<?php

namespace App\Libraries;

class SshClient
{
    private string $privateKey = '/var/www/.ssh/cross-servers.key';
    private string $publicKey  = '/var/www/.ssh/cross-servers.key.pub';

    /**
     * Execute a command on a remote server via SSH
     *
     * @param string $host Remote host
     * @param string $command Command to run
     * @param string $user SSH user (default: root)
     * @return string Command output
     * @throws \RuntimeException
     */
    public function runCommand(string $host, string $command, string $user = 'root'): string
    {
        $conn = ssh2_connect($host, 22);
        if (!$conn) {
            throw new \RuntimeException("SSH connect failed to $host");
        }

        if (!ssh2_auth_pubkey_file($conn, $user, $this->publicKey, $this->privateKey)) {
            throw new \RuntimeException("SSH auth failed for $user@$host");
        }

        $stream = ssh2_exec($conn, $command);
        if (!$stream) {
            throw new \RuntimeException("Failed to execute command: $command");
        }

        stream_set_blocking($stream, true);
        $output = stream_get_contents($stream);
        fclose($stream);

        return $output;
    }
}
