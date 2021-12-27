<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class InfoController extends ResourceController
{
   
    public function show($user_id = null)
    {
        $res=array();

        $res[]=$this->showOrdenesPendientes($user_id);
        $res[]=$this->showOrdenesRealizadas($user_id);
        $res[]=$this->showOrdenesMinutos($user_id);
        $res[]=$this->showOrdenesPiezas($user_id);
        $res[]=$this->showOrdenesPiezasValor($user_id);
        $res[]=$this->showFaltasPiezasValor($user_id);

        return $this->exito('Valor piezas utilizadas', $res);
    }

    public function showOrdenesPendientes($user_id = null)
    {
        $db = \Config\Database::connect();

        $sql="
            select
                count(o.id) as num
            from
                ordenes o
                left join
                orden_tecnicos t
                on
                    t.orden_id = o.id                
            where
                o.created_at >= date_add(now(), INTERVAL -1 MONTH ) and
                t.user_id = ? and
                o.estado<2
        ";

        $query   = $db->query($sql, $user_id);
        $results = $query->getResultArray();

        return $results[0];
    }

    public function showOrdenesRealizadas($user_id = null)
    {
        $db = \Config\Database::connect();

        $sql="
            select
                count(o.id) as num
            from
                ordenes o
                left join
                orden_tecnicos t
                on
                    t.orden_id = o.id                
            where
                o.created_at >= date_add(now(), INTERVAL -1 MONTH ) and
                t.user_id = ? and
                o.estado>=2
        ";
        
        $query   = $db->query($sql, $user_id);
        $results = $query->getResultArray();

        return $results[0];
    }

    public function showOrdenesMinutos($user_id = null)
    {
        $db = \Config\Database::connect();

        $sql="
            select
                sum(t.minutos) as num
            from
                ordenes o
                left join
                orden_tecnicos t
                on
                    t.orden_id = o.id                
            where
                o.created_at >= date_add(now(), INTERVAL -1 MONTH ) and
                t.user_id = ?
        ";
        
        $query   = $db->query($sql, $user_id);
        $results = $query->getResultArray();

        return $results[0];
    }

    public function showOrdenesPiezas($user_id = null)
    {
        $db = \Config\Database::connect();

        $sql="
            select
                sum(p.cantidad) as num
            from
                ordenes o
                left join
                orden_tecnicos t
                on
                    t.orden_id = o.id
                left join
                orden_productos p
                on
                    p.orden_id=o.id                
            where
                o.created_at >= date_add(now(), INTERVAL -1 MONTH ) and
                t.user_id = ?
        ";
        
        $query   = $db->query($sql, $user_id);
        $results = $query->getResultArray();

        return $results[0];
    }

    public function showOrdenesPiezasValor($user_id = null)
    {
        $db = \Config\Database::connect();

        $sql="
            select
                round(sum(p.cantidad*i.valor),2) as num
            from
                ordenes o
                left join
                orden_tecnicos t
                on
                    t.orden_id = o.id
                left join
                orden_productos p
                on
                    p.orden_id=o.id   
                left join
                inventario i
                on
                    i.producto_id=p.producto_id and
                    i.centro_id=o.centro_id             
            where
                o.created_at >= date_add(now(), INTERVAL -1 MONTH ) and
                t.user_id = ?
        ";
        
        $query   = $db->query($sql, $user_id);
        $results = $query->getResultArray();

        return $results[0];
    }

    public function showFaltasPiezasValor($user_id = null)
    {
        $db = \Config\Database::connect();

        $sql="
            select
                round(sum(p.cantidad*i.valor),2) as num
            from
                faltas f
                left join
                falta_productos p
                on
                    p.falta_id=f.id   
                left join
                inventario i
                on
                    i.producto_id=p.producto_id and
                    i.centro_id=f.centro_id             
            where
                f.created_at >= date_add(now(), INTERVAL -1 MONTH ) and
                f.solicitante_id = ?
        ";
        
        $query   = $db->query($sql, $user_id);
        $results = $query->getResultArray();

        return $results[0];
        
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
