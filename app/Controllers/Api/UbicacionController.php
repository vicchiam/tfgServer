<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

use App\Models\Ubicacion;

class UbicacionController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $ubicacion = new Ubicacion();

        return $this->respondCreated([
			'status' => 200,
			"error" => false,
			'messages' => 'Ubicaciones list',
			'data' => $ubicacion
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
        $ubicacion = new Ubicacion();

        $data = $ubicacion->find($id);

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

    
}
