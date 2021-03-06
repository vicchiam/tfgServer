<?php

/*
    php spark make:model (name)

    type (1,Administrador), (2,Operario), (3,Tecnico)
*/

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'centro_id',
        'username',
        'name',
        'email',
        'password',
        'type'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = ['hiddenPassword'];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function hiddenPassword(array $data){        

        if($data['method']=='first'){
            unset($data['password']);
        }
        if($data['method']=='findAll'){
            foreach($data['data'] as $key => $d){
                unset($data['data'][$key]['password']);
            }
        }
        return $data;
    }

}
