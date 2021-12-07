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

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}
