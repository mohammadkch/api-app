<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
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
     * Get user by username and password
     */
    public function getUser(array $where)
    {
        return $this->where($where)->first();
    }

    /**
     * Get user by username
     */
    public function getByUsername(string $username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Update last login time
     */
    public function updateLastLogin(int $userId)
    {
        return $this->update($userId, [
            'last_login' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get all users
     */
    public function getAll()
    {
        return $this->findAll();
    }
}