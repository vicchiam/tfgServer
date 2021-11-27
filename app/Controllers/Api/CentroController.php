<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

use App\Models\Centro;

class CentroController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $centro = new Centro();

        return $this->respondCreated([
			'status' => 200,
			"error" => false,
			'messages' => 'Centro list',
			'data' => $centro
                ->orderBy('nombre', 'asc')
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
        $centro = new Centro();

        $data = $centro->find($id);

        if( empty($data) ){
            return $this->respondCreated([
                'status' => 500,
                'error' => true,
                'messages' => 'Centro not found',
                'data' => []
            ]);
        }

        return $this->respondCreated([
            'status' => 200,
            'error' => false,
            'messages' => 'Single Centro data',
            'data' => $data
        ]);

    }

}
