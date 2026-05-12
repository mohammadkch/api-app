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
        'last_login',
        'avatar',
        'city_id'
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
        return $this->select('user.*, cities.city_name, cities.city_id, states.state_id, states.state_name')
            ->join('cities', 'cities.city_id = user.city_id', 'left')
            ->join('states', 'states.state_id = cities.state_id', 'left')
            ->where($where)
            ->first();
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

    public function getData($where = [], $limit = null, $offset = 0, $count = false)
    {
        $builder = $this->db->table($this->table);

        if (array_key_exists('username', $where)) {
            $builder->like('username', $where['username']);
            unset($where['username']);
        }

        if (array_key_exists('full_name', $where)) {
            $builder->like('full_name', $where['full_name']);
            unset($where['full_name']);
        }

        if (array_key_exists('role', $where)) {
            $builder->like('role', $where['role']);
            unset($where['role']);
        }

        if (array_key_exists('city_id', $where)) {
            $builder->where('user.city_id', $where['city_id']);
            unset($where['city_id']);
        }

        if ($count) {
            $builder->selectCount($this->primaryKey, 'total_rows');
            $builder->where($where);

            $query = $builder->get();
            $result = $query->getRowArray();

            return $result['total_rows'];
        }

        $builder->select('user.*, cities.city_name');
        $builder->join('cities', 'cities.city_id = user.city_id', 'left');

        $builder->where($where);

        $query = $builder->get($limit, $offset);

        $result = $query->getResultArray();

        return $result;
    }
}