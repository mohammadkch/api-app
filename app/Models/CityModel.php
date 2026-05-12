<?php

namespace App\Models;

use CodeIgniter\Model;

class CityModel extends Model
{
    protected $table = 'cities';
    protected $primaryKey = 'city_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'state_id',
        'city_name'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'int';

    /**
     * دریافت شهرهای یک استان
     */
    public function getByState($state_id)
    {
        return $this->where('state_id', $state_id)
            ->orderBy('city_name', 'ASC')
            ->findAll();
    }

    /**
     * دریافت همه شهرها همراه با نام استان
     */
    public function getAllWithState()
    {
        return $this->select('cities.*, states.state_name')
            ->join('states', 'states.state_id = cities.state_id')
            ->orderBy('states.state_name', 'ASC')
            ->orderBy('cities.city_name', 'ASC')
            ->findAll();
    }
}