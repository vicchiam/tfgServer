<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

use App\Models\Falta;
use App\Models\FaltaProducto;

class FaltaController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $falta = new Falta();

        return $this->exito('Falta list',$falta->findAll());
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $falta = new Falta();

        $data = $falta->find($id);

        if( empty($data) )
            return $this->error('Falta not found');

        return $this->exito('Single Falta data', $data);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function showByFecha($fecha)
    {
        $falta = new Falta();

        return $this->exito(
            'Falta by fecha list',
            $falta
                ->where('created_at >',$fecha)
                ->findAll()
            );
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        helper(['form']);
        $rules = ['centro_id' => 'required'];
        if(!$this->validate($rules)) 
            return $this->fail($this->validator->getErrors());

        $centro_id = $this->request->getVar('centro_id');
        
        $falta = new Falta();

        $data = [
            'centro_id' => $centro_id
        ];
        $result = $falta->update($id, $data);
        if( !$result )
            return $this->error('Error al guardar la falta '.$id);
        return $this->exito('Falta guardado',$result);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $falta = new Falta();

        $result = $falta->delete($id);

        if( !$result )
            return $this->error('Error al eliminar '.$id.' -> '.$result);
        
        return $this->exito('Eliminado correctamente', $result);
    }

    public function showProductos($id = null)
    {
        $faltaProducto = new FaltaProducto();

        $data = $faltaProducto
            ->where('falta_id',$id)
            ->orderBy('created_at')
            ->findAll();

        return $this->exito('Listado de productos de la falta '.$id, $data);
    }

    public function addProducto()
    {
        helper(['form']);
        $rules = [
            'falta_id' => 'required',
            'producto_id' => 'required',
            'cantidad' => 'required'
        ];
        if(!$this->validate($rules)) 
            return $this->fail($this->validator->getErrors());

        $falta_id = $this->request->getVar('falta_id');
        $producto_id = $this->request->getVar('producto_id');
        $cantidad = $this->request->getVar('cantidad');

        if( $cantidad < 0 )
            return $this->error('La cantidad no puede ser mayor que 0 y es '.$cantidad);

        $faltaProducto = new FaltaProducto();

        $existe = $faltaProducto
            ->where('falta_id', $falta_id)
            ->where('producto_id', $producto_id)
            ->first();

            $data = [
                'falta_id' => $falta_id,
                'producto_id' => $producto_id,
                'cantidad' => $cantidad
            ];

        if( $existe ){
            return $this->error('Este producto ya existe');
        }

        $result = $faltaProducto->insert($data);
        return $this->exito('Producto guardado', $result);
    }

    public function updateProducto()
    {
        helper(['form']);
        $rules = [
            'falta_id' => 'required',
            'producto_id' => 'required',
            'cantidad' => 'required'
        ];
        if(!$this->validate($rules)) 
            return $this->fail($this->validator->getErrors());

        $falta_id = $this->request->getVar('falta_id');
        $producto_id = $this->request->getVar('producto_id');
        $cantidad = $this->request->getVar('cantidad');

        if( $cantidad < 0 )
            return $this->error('La cantidad no puede ser mayor que 0 y es '.$cantidad);

        $faltaProducto = new FaltaProducto();

        $existe = $faltaProducto
            ->where('falta_id', $falta_id)
            ->where('producto_id', $producto_id)
            ->first();

        if( !$existe ){
            return $this->error('Producto no encontrado', $result);
        }

        $cantidadNew = $existe['cantidad'] + $cantidad;
        $data = [
            'cantidad' => $cantidad
        ];
        $result = $faltaProducto->update($existe['id'], $data);
        return $this->exito('Falta guardada', $result);
    }

    public function deleteProducto($id = null)
    {
        $faltaProducto = new FaltaProducto();

        $result = $faltaProducto->delete($id);

        if( !$result )
            return $this->error('Error al eliminar '.$id.' -> '.$result);
        
        return $this->exito('Eliminado correctamente', $result);
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
