<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'admins';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'username',
        'password',
        'full_name',
        'role',
        'last_login'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = null;

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Get admin by username and password
     */
    public function getAdmin(array $where)
    {
        return $this->where($where)->first();
    }

    /**
     * Get admin by username
     */
    public function getByUsername(string $username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Update last login time
     */
    public function updateLastLogin(int $adminId)
    {
        return $this->update($adminId, [
            'last_login' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get all admins
     */
    public function getAll()
    {
        return $this->findAll();
    }
}