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

    public function showWithInventario(){
        $db = \Config\Database::connect();

        $sql="
            select
                p.id,                
                p.descripcion,
                ip.cantidad as picassent,
                im.cantidad as merca,
                it.cantidad as teruel,
                ip.valor as vpicassent,
                im.valor as vmerca,
                it.valor as vteruel
            from
                productos p
                left join
                inventario ip
                on
                    ip.producto_id=p.id and ip.centro_id=1
                left join
                inventario im
                on
                    im.producto_id=p.id and im.centro_id=2
                left join
                inventario it
                on
                    it.producto_id=p.id and it.centro_id=3
            order by
                p.descripcion
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
