<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

use App\Models\Orden;
use App\Models\OrdenTecnico;
use App\Models\OrdenProducto;

class OrdenController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $orden = new Orden();

        return $this->respondCreated([
			'status' => 200,
			"error" => false,
			'messages' => 'Ordenes list',
			'data' => $orden
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
        $db = \Config\Database::connect();        

        $sql="
            select
                o.*,
                (select u.descripcion from ubicaciones u where u.id=o.ubicacion_id) as ubicacion_nom,
                (select m.descripcion from maquinas m where m.id=o.maquina_id) as maquina_nom,
                (select i.descripcion from instalaciones i where i.id=o.instalacion_id) as instalacion_nom
            from
                ordenes o
            where
                o.id= ?
        ";
        $query   = $db->query($sql, $id);
        $results = $query->getResultArray();

        return $this->exito('Orden '.$id, $results[0]);
    }

    public function filter(){
        helper(['form']);
        $rules = [                     
            'desde' => 'required',
            'hasta' => 'required'
        ];
        if(!$this->validate($rules)) 
            return $this->fail($this->validator->getErrors());

        $tipo = $this->request->getVar('tipo');
        $usuario = $this->request->getVar('usuario');
        $centro = $this->request->getVar('centro');
        $desde = $this->request->getVar('desde');
        $hasta = $this->request->getVar('hasta');        
        $estado = $this->request->getVar('estado');

        $db = \Config\Database::connect();

        $where="";
        $whereParams = [$desde, $hasta];

        if($tipo != ''){
            $where.=" and o.tipo= ? ";
            $whereParams[]=$tipo;
        }
        if($usuario != ''){
            $where.=" and ( o.solicitante_id= ? or t.user_id= ? )";
            $whereParams[]=$usuario;            
            $whereParams[]=$usuario;
        }
        if($centro != ''){
            $where.=" and o.centro_id= ? ";
            $whereParams[]=$centro;
        }
        if($estado == '-1'){
            $where.=" and o.estado<> ? ";
            $whereParams[]=99;
        }   
        else if($estado != ''){
            $where.=" and o.estado= ? ";
            $whereParams[]=$estado;
        }
            

        $sql="
            select
                o.id,
                o.solicitante_id,
                o.tipo,
                o.estado,
                o.averia,
                o.fecha_inicio,
                o.created_at
            from
                ordenes o
                left join
                orden_tecnicos t
                on
                    t.orden_id = o.id                
            where
                o.created_at >= ? and
                o.created_at <= ?
                ".$where."
            group by
                o.id,
                o.solicitante_id,
                o.tipo,
                o.estado,
                o.averia,
                o.fecha_inicio,
                o.created_at
            order by
                o.created_at desc
            limit 50
        ";

        $query   = $db->query($sql, $whereParams);
        $results = $query->getResultArray();

        return $this->exito('Listado de ordenes filtradas', $results);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        helper(['form']);
        $rules = [
            'tipo' => 'required',
            'solicitante_id' => 'required',
            'centro_id' => 'required',
            'ubicacion_id' => 'required',
            'maq_inst' => 'required',            
            'averia' => 'required',
            'estado' => 'required'
        ];
        if(!$this->validate($rules)) 
            return $this->fail($this->validator->getErrors());

        $tipo = $this->request->getVar('tipo');
        $solicitante_id = $this->request->getVar('solicitante_id');
        $centro_id = $this->request->getVar('centro_id');
        $ubicacion_id = $this->request->getVar('ubicacion_id');
        $maq_inst = $this->request->getVar('maq_inst');
        $maquina_id = $this->request->getVar('maquina_id');
        $instalacion_id = $this->request->getVar('instalacion_id');
        $averia = $this->request->getVar('averia');
        $trabajo = $this->request->getVar('trabajo');
        $fecha_inicio = $this->request->getVar('fecha_inicio');
        $fecha_fin = $this->request->getVar('fecha_fin');
        $parada = $this->request->getVar('parada');
        $estado = $this->request->getVar('estado');

        $orden = new Orden();

        $data = [
            'tipo' => $tipo,
            'solicitante_id' => $solicitante_id,
            'centro_id' => $centro_id,
            'ubicacion_id' => $ubicacion_id,
            'maq_inst' => $maq_inst,
            'maquina_id' => $maquina_id,
            'instalacion_id' => $instalacion_id,
            'averia' => $averia,
            'trabajo' => ( ($trabajo==null)?'':$trabajo ),
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'parada' => ( ($parada==null)?'':$parada ),
            'estado' => $estado
        ];

        $result = $orden->insert($data);
        if( !$result )
            return $this->error('Error al guardar la orden '.$id);
        return $this->exito('Orden guardada',$result);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        helper(['form']);
        $rules = [
            'tipo' => 'required',
            'solicitante_id' => 'required',
            'centro_id' => 'required',
            'ubicacion_id' => 'required',
            'maq_inst' => 'required',            
            'averia' => 'required',            
            'estado' => 'required'
        ];
        if(!$this->validate($rules)) 
            return $this->fail($this->validator->getErrors());        

        $tipo = $this->request->getVar('tipo');
        $solicitante_id = $this->request->getVar('solicitante_id');
        $centro_id = $this->request->getVar('centro_id');
        $ubicacion_id = $this->request->getVar('ubicacion_id');
        $maq_inst = $this->request->getVar('maq_inst');
        $maquina_id = $this->request->getVar('maquina_id');
        $instalacion_id = $this->request->getVar('instalacion_id');
        $averia = $this->request->getVar('averia');
        $trabajo = $this->request->getVar('trabajo');
        $fecha_inicio = $this->request->getVar('fecha_inicio');
        $fecha_fin = $this->request->getVar('fecha_fin');
        $parada = $this->request->getVar('parada');
        $estado = $this->request->getVar('estado');

        if(!empty($fecha_fin)){
            $ordenTecnico = new OrdenTecnico();
            $exists = $ordenTecnico->where('orden_id', $id)->findAll();
            if(count($exists)==0)
                return $this->error('No se puede cerrar la orden sin asignar ningún técnico');
        }

        $orden = new Orden();

        $data = [
            'tipo' => $tipo,
            'solicitante_id' => $solicitante_id,
            'centro_id' => $centro_id,
            'ubicacion_id' => $ubicacion_id,
            'maq_inst' => $maq_inst,
            'maquina_id' => $maquina_id,
            'instalacion_id' => $instalacion_id,
            'averia' => $averia,
            'trabajo' => ( ($trabajo==null)?'':$trabajo ),
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'parada' => ( ($parada==null)?'':$parada ),
            'estado' => $estado
        ];

        $result = $orden->update($id,$data);
        if( !$result )
            return $this->error('Error al guardar la orden '.$id);
        return $this->exito('Orden guardada',$result);
    }

    public function updateEstado($id = null){
        helper(['form']);        
        $rules = [            
            'estado' => 'required'
        ];
        if(!$this->validate($rules)) 
            return $this->fail($this->validator->getErrors());
        
        $estado = $this->request->getVar('estado');         
        $razon = $this->request->getVar('razon');        

        $orden = new Orden();

        $data = [            
            'estado' => $estado,
            'razon' => $razon
        ];

        $result = $orden->update($id,$data);
        if( !$result )
            return $this->error('Error al guardar la orden '.$id);
        return $this->exito('Orden guardada',$result);
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

    public function showTecnicos($orden_id = null)
    {
        $db = \Config\Database::connect();

        $sql="
            select
                t.id,
                u.name,
                t.user_id,
                t.fecha,
                t.minutos
            from
                orden_tecnicos t
                left join
                users u
                on
                    u.id=t.user_id
            where
                t.orden_id = ?
        ";

        $query   = $db->query($sql, $orden_id);
        $results = $query->getResultArray();

        return $this->exito('Listado de tecnicos de la orden: '.$orden_id, $results);
    }

    public function addTecnico()
    {
        helper(['form']);
        $rules = [
            'orden_id' => 'required',
            'user_id' => 'required',
            'fecha' => 'required',
            'minutos' => 'required'
        ];
        if(!$this->validate($rules)) 
            return $this->fail($this->validator->getErrors());

        $orden_id = $this->request->getVar('orden_id');
        $user_id = $this->request->getVar('user_id');
        $fecha = $this->request->getVar('fecha');
        $minutos = $this->request->getVar('minutos');

        if( $minutos < 0 )
            return $this->error('Los minutos no pueden ser menor que 0 y es '.$minutos);

        $ordenTecnico = new OrdenTecnico();

        $data = [
            'orden_id' => $orden_id,
            'user_id' => $user_id,
            'fecha' => $fecha,
            'minutos' => $minutos
        ];       

        $result = $ordenTecnico->insert($data);
        return $this->exito('Tecnico guardado', $result);
    }

    public function updateTecnico($id = null)
    {
        helper(['form']);
        $rules = [
            'orden_id' => 'required',
            'user_id' => 'required',
            'fecha' => 'required',
            'minutos' => 'required'
        ];
        if(!$this->validate($rules)) 
            return $this->fail($this->validator->getErrors());

        $orden_id = $this->request->getVar('orden_id');
        $user_id = $this->request->getVar('user_id');
        $fecha = $this->request->getVar('fecha');
        $minutos = $this->request->getVar('minutos');

        if( $minutos < 0 )
            return $this->error('Los minutos no pueden ser menor que 0 y es '.$minutos);

        $ordenTecnico = new OrdenTecnico();

        $data = [
            'orden_id' => $orden_id,
            'user_id' => $user_id,
            'fecha' => $fecha,
            'minutos' => $minutos
        ];       

        $result = $ordenTecnico->update($id, $data);
        return $this->exito('Tecnico guardado', $result);
    }

    public function deleteTecnico($id = null)
    {
        $ordenTecnico = new OrdenTecnico();

        $result = $ordenTecnico->delete($id);

        if( !$result )
            return $this->error('Error al eliminar '.$id.' -> '.$result);
        
        return $this->exito('Eliminado correctamente', $result);
    }

    public function showProductos($orden_id = null){
        $db = \Config\Database::connect();

        $sql="
            select
                t.id,                
                p.descripcion,
                t.producto_id,
                t.cantidad,
                ip.cantidad as picassent,
                im.cantidad as merca,
                it.cantidad as teruel
            from
                orden_productos t
                left join
                productos p
                on
                    p.id=t.producto_id
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
            where
                t.orden_id = ?
        ";

        $query   = $db->query($sql, $orden_id);
        $results = $query->getResultArray();

        return $this->exito('Listado de productos de la orden: '.$orden_id, $results);
    }

    public function addProducto()
    {
        helper(['form']);
        $rules = [
            'orden_id' => 'required',
            'producto_id' => 'required',
            'cantidad' => 'required'
        ];
        if(!$this->validate($rules)) 
            return $this->fail($this->validator->getErrors());

        $orden_id = $this->request->getVar('orden_id');
        $producto_id = $this->request->getVar('producto_id');        
        $cantidad = $this->request->getVar('cantidad');

        if( $cantidad < 0 )
            return $this->error('La cantidad no pueden ser menor que 0 y es '.$cantidad);

        $ordenProducto = new OrdenProducto();

        $data = [
            'orden_id' => $orden_id,
            'producto_id' => $producto_id,            
            'cantidad' => $cantidad
        ];       

        $result = $ordenProducto->insert($data);
        return $this->exito('Producto guardado', $result);
    }

    public function updateProducto($id = null)
    {
        helper(['form']);
        $rules = [
            'orden_id' => 'required',
            'producto_id' => 'required',
            'cantidad' => 'required'
        ];
        if(!$this->validate($rules)) 
            return $this->fail($this->validator->getErrors());

        $orden_id = $this->request->getVar('orden_id');
        $producto_id = $this->request->getVar('producto_id');        
        $cantidad = $this->request->getVar('cantidad');

        if( $cantidad < 0 )
            return $this->error('La cantidad no pueden ser menor que 0 y es '.$cantidad);

        $ordenProducto = new OrdenProducto();

        $data = [
            'orden_id' => $orden_id,
            'producto_id' => $producto_id,            
            'cantidad' => $cantidad
        ];       

        $result = $ordenProducto->update($id, $data);
        return $this->exito('Producto guardado', $result);
    }

    public function deleteProducto($id = null)
    {
        $ordenProducto = new OrdenProducto();

        $result = $ordenProducto->delete($id);

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
