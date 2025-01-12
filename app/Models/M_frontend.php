<?php namespace App\Models;

use CodeIgniter\Model;

class M_frontend extends Model{
    protected $table      = 'tb_user';
    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'nis', 'nama', 'password', 'email', 'status', 'jurusan', 'no_hp', 'jenis_kelamin', 'foto', 'kelas'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

}