<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountModel extends Model
{
    protected $table            = 'accounts';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'server_id', 'inbound_id', 'client_name', 'protocol',
        'uuid', 'traffic_limit_gb', 'traffic_total_bytes',
        'expiry_date', 'config_link', 'qr_path', 'status'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /**
     * Get account with server details
     */
    public function getAccountWithServer(string $clientName)
    {
        return $this->select('accounts.*, servers.ip_address, servers.type as server_type')
            ->join('servers', 'servers.id = accounts.server_id')
            ->where('accounts.client_name', $clientName)
            ->first();
    }
}