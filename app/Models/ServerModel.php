<?php

namespace App\Models;

use CodeIgniter\Model;

class ServerModel extends Model
{
    protected $table            = 'servers';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'ip_address', 'ssh_user', 'type', 'status'];
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
}