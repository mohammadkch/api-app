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
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'int';  // UNIX Timestamp

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function getUser(array $where)
    {
        return $this->where($where)->first();
    }

    public function getByUsername(string $username)
    {
        return $this->where('username', $username)->first();
    }

    public function updateLastLogin(int $userId)
    {
        return $this->update($userId, [
            'last_login' => time()
        ]);
    }

    public function getAll()
    {
        return $this->findAll();
    }
}