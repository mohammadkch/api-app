<?php

namespace App\Models;

use CodeIgniter\Model;

class ServerModel extends Model
{
    protected $table            = 'servers';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'name',
        'ip_address',
        'ssh_port',
        'ssh_user',
        'vpn_type',      // 'openvpn' یا 'xui'
        'vpn_port',
        'tunnel_domain',
        'status'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = null;  // servers جدول آپدیت نداره
    protected $dateFormat       = 'datetime';

    /**
     * Get active OpenVPN servers
     */
    public function getOpenVpnServers()
    {
        return $this->where('vpn_type', 'openvpn')
            ->where('status', 1)
            ->findAll();
    }

    /**
     * Get active XUI servers
     */
    public function getXuiServers()
    {
        return $this->where('vpn_type', 'xui')
            ->where('status', 1)
            ->findAll();
    }

    /**
     * Get server by ID
     */
    public function getServerById(int $serverId)
    {
        return $this->find($serverId);
    }
}