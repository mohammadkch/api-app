<?php

namespace App\Models;

use CodeIgniter\Model;

class StateModel extends Model
{
    protected $table = 'states';
    protected $primaryKey = 'state_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'state_name'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'int';

    /**
     * دریافت همه استان‌ها
     */
    public function getAll()
    {
        return $this->orderBy('state_name', 'ASC')->findAll();
    }

    /**
     * دریافت استان به همراه شهرهایش
     */
    public function getWithCities($state_id)
    {
        return $this->select('states.*, cities.city_id, cities.city_name')
            ->join('cities', 'cities.state_id = states.state_id', 'left')
            ->where('states.state_id', $state_id)
            ->findAll();
    }
}