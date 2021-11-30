<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

use App\Models\Instalacion;

class InstalacionController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $instalacion = new Instalacion();

        return $this->respondCreated([
			'status' => 200,
			"error" => false,
			'messages' => 'Instalacion list',
			'data' => $instalacion
                ->orderBy('codigo', 'asc')
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
        $instalacion = new Instalacion();

        $data = $instalacion->find($id);

        if( empty($data) ){
            return $this->respondCreated([
                'status' => 500,
                'error' => true,
                'messages' => 'Instalacion not found',
                'data' => []
            ]);          
        }

        return $this->respondCreated([
            'status' => 200,
            'error' => false,
            'messages' => 'Single Instalacion data',
            'data' => $data
        ]);
    }

    public function showByCode($code = null){
        $instalacion = new Instalacion();

        return $this->respondCreated([
			'status' => 200,
			"error" => false,
			'messages' => 'Instalacion by Code list',
			'data' => $instalacion
                ->where('codigo', $code)
                ->orderBy('codigo', 'asc')
                ->findAll()
		]);
    }

    public function showByDescription($description = null){
        $db = \Config\Database::connect();

        $sql="
            select
                id,
                codigo,
                descripcion,
                centro_id
            from
                instalaciones
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
			'messages' => 'Instalacion like description list',
			'data' => $results
		]);
    }

    public function showByCentro($centro_id = null){
        $instalacion = new Instalacion();

        return $this->respondCreated([
			'status' => 200,
			"error" => false,
			'messages' => 'Instalacion by Code list',
			'data' => $instalacion
                ->where('centro_id', $centro_id)
                ->orderBy('codigo', 'asc')
                ->findAll()
		]);
    }

}
