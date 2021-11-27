<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

use App\Models\Producto;

class ProductoController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        
        $producto = new Producto();

        return $this->respondCreated([
			'status' => 200,
			"error" => false,
			'messages' => 'Producto list',
			'data' => $producto
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
        $producto = new Producto();

        $data = $producto->find($id);

        if( empty($data) ){
            return $this->respondCreated([
                'status' => 500,
                'error' => true,
                'messages' => 'Producto not found',
                'data' => []
            ]);          
        }

        return $this->respondCreated([
            'status' => 200,
            'error' => false,
            'messages' => 'Single Producto data',
            'data' => $data
        ]);
    }

    public function showByCode($code = null){
        $producto = new Producto();

        return $this->respondCreated([
			'status' => 200,
			"error" => false,
			'messages' => 'Producto by Code list',
			'data' => $producto
                ->where('codigo', $code)
                ->findAll()
		]);
    }

    public function showByDescription($description = null){
        $db = \Config\Database::connect();

        $sql="
            select
                id,
                codigo,
                descripcion
            from
                productos
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
			'messages' => 'Producto like description list',
			'data' => $results
		]);
    }

   
}
