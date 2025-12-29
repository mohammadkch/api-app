<?php

namespace App\Libraries;

use RuntimeException;

class SshClient
{
    private string $privateKey = '/var/www/.ssh/cross-servers.key';
    private string $publicKey  = '/var/www/.ssh/cross-servers.key.pub';
    private int $port = 22;

    /**
     * Execute a command on a remote server via SSH
     *
     * @param string $host Remote host
     * @param string $command Command to run
     * @param string $user SSH user (default: root)
     * @return string Command output
     * @throws RuntimeException
     */
    public function runCommand(string $host, string $command, string $user = 'root'): string
    {
        $conn = $this->connect($host, $user);

        $stream = ssh2_exec($conn, $command);
        if (!$stream) {
            throw new RuntimeException("Failed to execute command: $command");
        }

        stream_set_blocking($stream, true);
        $output = stream_get_contents($stream);
        fclose($stream);

        return $output;
    }

    /**
     * Download a file from the remote server via SCP
     *
     * @param string $host Remote host
     * @param string $remoteFile Full remote file path
     * @param string $localFile Full local file path
     * @param string $user SSH user (default: root)
     * @throws RuntimeException
     */
    public function downloadFile(string $host, string $remoteFile, string $localFile, string $user = 'root'): void
    {
        $conn = $this->connect($host, $user);

        if (!ssh2_scp_recv($conn, $remoteFile, $localFile)) {
            throw new RuntimeException("SCP download failed: $remoteFile to $localFile");
        }
    }

    /**
     * Connect to a remote server via SSH
     *
     * @param string $host
     * @param string $user
     * @return resource
     * @throws RuntimeException
     */
    private function connect(string $host, string $user)
    {
        $conn = ssh2_connect($host, $this->port);
        if (!$conn) {
            throw new RuntimeException("SSH connect failed to $host");
        }

        if (!ssh2_auth_pubkey_file($conn, $user, $this->publicKey, $this->privateKey)) {
            throw new RuntimeException("SSH auth failed for $user@$host");
        }

        return $conn;
    }
}
