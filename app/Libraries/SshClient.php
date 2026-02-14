<?php

namespace App\Libraries;

use RuntimeException;

class SshClient
{
    private string $privateKey = '/var/www/.ssh/cross-servers.key';
    private int $port = 22;

    public function runCommand(string $host, string $command, string $user = 'root'): string
    {
        $sshCommand = sprintf(
            'ssh -i %s -o StrictHostKeyChecking=no -p %d %s@%s %s 2>&1',
            escapeshellarg($this->privateKey),
            $this->port,
            escapeshellarg($user),
            escapeshellarg($host),
            escapeshellarg($command)
        );

        exec($sshCommand, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new RuntimeException("SSH command failed: " . implode("\n", $output));
        }

        return implode("\n", $output);
    }

    public function downloadFile(string $host, string $remoteFile, string $localFile, string $user = 'root'): void
    {
        $scpCommand = sprintf(
            'scp -i %s -o StrictHostKeyChecking=no -P %d %s@%s:%s %s 2>&1',
            escapeshellarg($this->privateKey),
            $this->port,
            escapeshellarg($user),
            escapeshellarg($host),
            escapeshellarg($remoteFile),
            escapeshellarg($localFile)
        );

        exec($scpCommand, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new RuntimeException("SCP download failed: " . implode("\n", $output));
        }
    }
}