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

    public function filter(){
        helper(['form']);
        $rules = [                     
            'desde' => 'required',
            'hasta' => 'required'
        ];
        if(!$this->validate($rules)) 
            return $this->fail($this->validator->getErrors());

        $usuario = $this->request->getVar('usuario');
        $centro = $this->request->getVar('centro');
        $desde = $this->request->getVar('desde');
        $hasta = $this->request->getVar('hasta');                

        $db = \Config\Database::connect();

        $where="";
        $whereParams = [$desde, $hasta];

        if($usuario != ''){
            $where.=" and f.solicitante_id= ?";
            $whereParams[]=$usuario;
        }
        if($centro != ''){
            $where.=" and f.centro_id= ? ";
            $whereParams[]=$centro;
        }                   

        $sql="
            select
                f.id,
                f.solicitante_id,
                (select u.name from users u where u.id=f.solicitante_id) as user_nom,
                f.centro_id,
                (select c.nombre from centros c where c.id=f.centro_id) as centro_nom,
                f.created_at,
                sum(fp.cantidad) as productos,
                round(sum(fp.cantidad*i.valor),2) as valor
            from
                faltas f
                left join
                falta_productos fp
                on
                    fp.falta_id=f.id
                left join
                inventario i
                on
                    i.centro_id=f.centro_id and
                    i.producto_id=fp.producto_id
            where
                f.created_at >= ? and
                f.created_at <= ?
                ".$where."            
            group by
                f.id,
                f.solicitante_id,                
                f.centro_id,                
                f.created_at
            order by
                f.created_at desc
            limit 50
        ";
        $query   = $db->query($sql, $whereParams);
        $results = $query->getResultArray();

        return $this->exito('Listado de faltas filtradas', $results);
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
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        helper(['form']);
        $rules = [
            'solicitante_id' => 'required',
            'centro_id' => 'required'
        ];
        if(!$this->validate($rules)) 
            return $this->fail($this->validator->getErrors());

        $solicitante_id = $this->request->getVar('solicitante_id');
        $centro_id = $this->request->getVar('centro_id');

        $falta = new Falta();

        $data = [
            'solicitante_id' => $solicitante_id,
            'centro_id' => $centro_id
        ];

        $result = $falta->insert($data);
        if( !$result )
            return $this->error('Error al guardar la falta '.$id);
        return $this->exito('Falta guardado',$result);
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

        $db = \Config\Database::connect();

        $sql="
        select
            t.id,                
            p.descripcion,
            t.producto_id,
            t.cantidad,
            round((t.cantidad * i.valor),1) as valor,
            ip.cantidad as picassent,
            im.cantidad as merca,
            it.cantidad as teruel,
            ip.valor as vpicassent,
            im.valor as vmerca,
            it.valor as vteruel
        from
            falta_productos t
            inner join
            faltas f
            on
                f.id=t.falta_id
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
            left join
            inventario i
            on
                i.producto_id=p.id and i.centro_id=f.centro_id
        where
            t.falta_id = ?
        ";
        $query   = $db->query($sql, $id);
        $results = $query->getResultArray();

        return $this->exito('Listado de prodcuto de la falta '.$id, $results);
    }

    public function save()
    {
        $json = json_decode(file_get_contents('php://input'), true);

        if($json["id"]==0){
            $falta = new Falta();
            $data = [
                'solicitante_id' => $json["solicitante_id"],
                'centro_id' => $json["centro_id"]
            ];
            $result = $falta->insert($data);

            foreach($json["productos"] as $p){
                $faltaProducto = new FaltaProducto();
                $data = [
                    'falta_id' => $result,
                    'producto_id' => $p["producto_id"],
                    'cantidad' => $p["cantidad"]
                ];
                $faltaProducto->insert($data);
            }

            return $this->exito('Producto guardado', $result);
        }
        else{            
            $falta = new Falta();
            $data = [
                'solicitante_id' => $json["solicitante_id"],
                'centro_id' => $json["centro_id"]
            ];
            $result = $falta->update($json["id"],$data);

            $faltaProducto = new FaltaProducto();

            $productos = $faltaProducto->where('falta_id',$json["id"])-> findAll();

            $ids = array();
            foreach($json["productos"] as $p){
                if($p["id"]==0){//Es nuevo
                    $data = [
                        'falta_id' => $json["id"],
                        'producto_id' => $p["producto_id"],
                        'cantidad' => $p["cantidad"]
                    ];
                    $res = $faltaProducto->insert($data);                    
                }
                else{//existe
                    $ids[]=$p["id"];
                    $data = [                        
                        'producto_id' => $p["producto_id"],
                        'cantidad' => $p["cantidad"]
                    ];
                    $faltaProducto->update($p["id"],$data);
                }
            }

            

            foreach($productos as $p){
                if(!in_array($p["id"],$ids)){
                    $faltaProducto->delete($p["id"]);
                }
            }
            return $this->exito('Producto guardado', $result);
        }
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
            return $this->error('La cantidad no puede ser menor que 0 y es '.$cantidad);

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
