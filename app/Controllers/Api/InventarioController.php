<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

use App\Models\Inventario;

class InventarioController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $inventario = new Inventario();

        return $this->respondCreated([
			'status' => 200,
			"error" => false,
			'messages' => 'Inventario list',
			'data' => $inventario
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
        $inventario = new Inventario();

        $data = $inventario->find($id);

        if( empty($data) ){
            return $this->error('Inventario not found');          
        }

        return $this->respondCreated([
            'status' => 200,
            'error' => false,
            'messages' => 'Single Inventario data',
            'data' => $data
        ]);
    }

    public function showByProducto($producto_id)
    {
        $inventario = new Inventario();

        $data = $inventario 
            -> where( 'producto_id', $producto_id )
            -> orderBy( 'centro_id')
            -> findAll();

        if(!$data)
            return $this->error('No se ha encontrado el inventario del producto '.$producto_id);

        return $this->exito('Inventario del producto '.$producto_id, $data);
    }

    public function add()
    {

        helper(['form']);
        $rules = [
            'producto_id' => 'required',
            'centro_id' => 'required',
            'cantidad' => 'required',
            'valor' => 'required'
        ];
        if(!$this->validate($rules)) 
            return $this->fail($this->validator->getErrors());
        
        $producto_id = $this->request->getVar('producto_id');
        $centro_id = $this->request->getVar('centro_id');
        $cantidad =  $this->request->getVar('cantidad');
        $valor = $this->request->getVar('valor');
        
        $inventario = new Inventario();

        $exist = $inventario
            ->where('centro_id', $centro_id)
            ->where('producto_id', $producto_id)
            ->first();
        
        $data = [
            'centro_id' => $centro_id,
            'producto_id' => $producto_id,
            'cantidad' => $cantidad,
            'valor' => $valor
        ];

        if( !$exist ){
            $result = $inventario->insert($data);
            return $this->exito('Inventario guardado',$result);
        }

        $cantidadNew = $exist["cantidad"] + $cantidad;
        $valorNew =
            ( ($exist["valor"] * $exist["cantidad"]) + ($cantidad*$valor)) / $cantidadNew;

        $data = [
            'cantidad' => $cantidadNew,
            'valor' => $valorNew
        ];
        $result = $inventario->update($exist["id"], $data);
        return $this->exito('Inventario guardado',$result);
    }

    public function substract()
    {
        helper(['form']);
        $rules = [
            'producto_id' => 'required',
            'centro_id' => 'required',
            'cantidad' => 'required'
        ];
        if(!$this->validate($rules)) 
            return $this->fail($this->validator->getErrors());

        $producto_id = $this->request->getVar('producto_id');
        $centro_id = $this->request->getVar('centro_id');
        $cantidad =  $this->request->getVar('cantidad');

        $inventario = new Inventario();

        $exist = $inventario
            ->where('centro_id', $centro_id)
            ->where('producto_id', $producto_id)
            ->first();

        if( $exist["cantidad"] < $cantidad)
            return $this->error('La cantidad a extraer es mayor que la existente');
        
        $cantidadNew = $exist["cantidad"] - $cantidad;
        $data = [
            'cantidad' => $cantidadNew
        ];
        $result = $inventario->update($exist["id"], $data);
        return $this->exito('Inventario guardado',$result);
    }

    private function error($msg)
    {
        return $this->respondCreated([
            'status' => 500,
            'error' => true,
            'messages' => $msg,
            'data' => []
        ]);
    }

    private function exito($msg, $data){
        return $this->respondCreated([
            'status' => 200,
            'error' => false,
            'messages' => $msg,
            'data' => $data
        ]);
    }

}
