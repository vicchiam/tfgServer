<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

use App\Models\Orden;
use App\Models\Producto;

class OrdenProductosSeeder extends Seeder
{
    public function run()
    {
        $model = model('OrdenProducto');

        $model->truncate();

        $orden = new Orden();
        $ordenes = count($orden->findAll());

        $producto = new Producto();
        $productos = count($producto->findAll());

        for($i=0; $i<100; $i++){
            $model->insert([
                'orden_id' => static::faker()->numberBetween(1,$ordenes), 
                'producto_id' => static::faker()->numberBetween(1,$productos),                
                'cantidad' => static::faker()->numberBetween(0, 50)
            ]);
        }
    }
}
