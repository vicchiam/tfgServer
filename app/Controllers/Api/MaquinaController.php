<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

use App\Models\Maquina;

class MaquinaController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $maquina = new Maquina();

        return $this->respondCreated([
			'status' => 200,
			"error" => false,
			'messages' => 'Maquina list',
			'data' => $maquina
                ->orderBy('descripcion', 'asc')
                ->findAll()
		]);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $maquina = new Maquina();

        $data = $maquina->find($id);

        if( empty($data) ){
            return $this->respondCreated([
                'status' => 500,
                'error' => true,
                'messages' => 'Ubicacion not found',
                'data' => []
            ]);          
        }

        return $this->respondCreated([
            'status' => 200,
            'error' => false,
            'messages' => 'Single Ubicacion data',
            'data' => $data
        ]);
    }

    public function showByUbicacion($ubicacion_id)
    {
        $maquina = new Maquina();

        return $this->respondCreated([
			'status' => 200,
			"error" => false,
			'messages' => 'Maquina list',
			'data' => $maquina
                ->where('ubicacion_id',$ubicacion_id)
                ->orderBy('descripcion', 'asc')
                ->findAll()
		]);
    }

    public function showByDescription($description = null){
        $db = \Config\Database::connect();

        $sql="
            select
                id,
                descripcion,
                ubicacion_id
            from
                maquinas
            where
                descripcion like '%".
                $db->escapeLikeString($description).
                "%' ESCAPE '!'
        ";

        $query   = $db->query($sql);
        $results = $query->getResultArray();

        return $this->respondCreated([
			'status' => 200,
			"error" => false,
			'messages' => 'Maquina like description list',
			'data' => $results
		]);
    }

    public function showByDescriptionUbicacion($description = null, $ubicacion_id){
        if($description=='*'){
            $description='';
        }

        $db = \Config\Database::connect();

        $sql="
            select
                id,
                descripcion,
                ubicacion_id
            from
                maquinas
            where
                descripcion like '%".
                $db->escapeLikeString($description).
                "%' ESCAPE '!'
                and ubicacion_id= ?
        ";

        $query   = $db->query($sql, $ubicacion_id);
        $results = $query->getResultArray();

        return $this->respondCreated([
			'status' => 200,
			"error" => false,
			'messages' => 'Ubicacion like description list',
			'data' => $results
		]);
    }

}
