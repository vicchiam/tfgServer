<?php

namespace App\Models;

use CodeIgniter\Model;

/*
    Tipo
    1 -> Correctivo, 2-> Preventivo, 3-> Movimiento, 4 -> Mejora
    Estados
    0-> Pendiente , 1-> Iniciada, 2-> Finalizada, 3-> Validada, 4-> Rechazada, 99-> Descartada  
*/

class Orden extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'ordenes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tipo',
        'solicitante_id',
        'centro_id',
        'ubicacion_id',        
        'maq_inst',    
        'maquina_id',
        'instalacion_id',
        'averia',
        'trabajo',    
        'fecha_inicio',
        'fecha_fin',
        'parada',
        'estado',
        'razon'
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
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
