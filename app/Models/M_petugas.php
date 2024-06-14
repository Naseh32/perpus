<?php namespace App\Models;

use CodeIgniter\Model;

class M_petugas extends Model{
    protected $table      = 'admin';
    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'nama', 'username', 'password', 'jenis_kelamin', 'level', 'status', 'foto'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

}