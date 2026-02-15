<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountModel extends Model
{
    protected $table            = 'accounts';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'server_id',
        'client_name',
        'file_name',           // "client.png" or "client.ovpn"
        'traffic_total_bytes',
        'traffic_used_bytes',
        'expiry_date',
        'metadata',            // JSON: protocol, uuid, inbound_id, config
        'status'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /**
     * Get account with server details
     */
    public function getAccountWithServer(string $clientName, int $serverId = null)
    {
        $builder = $this->select('accounts.*, servers.ip_address, servers.vpn_type, servers.vpn_port, servers.tunnel_domain')
            ->join('servers', 'servers.id = accounts.server_id')
            ->where('accounts.client_name', $clientName);

        if ($serverId) {
            $builder->where('accounts.server_id', $serverId);
        }

        return $builder->first();
    }

    /**
     * Get all accounts for a specific server
     */
    public function getAccountsByServer(int $serverId)
    {
        return $this->where('server_id', $serverId)->findAll();
    }

    /**
     * Get account by client name and server
     */
    public function getByClientAndServer(string $clientName, int $serverId)
    {
        return $this->where('client_name', $clientName)
            ->where('server_id', $serverId)
            ->first();
    }

    /**
     * Parse metadata JSON
     */
    public function parseMetadata(array $account)
    {
        if (isset($account['metadata'])) {
            $account['metadata'] = json_decode($account['metadata'], true);
        }
        return $account;
    }
}