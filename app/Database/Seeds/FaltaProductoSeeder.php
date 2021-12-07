<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

use App\Models\Producto;
use App\Models\Falta;

class FaltaProductoSeeder extends Seeder
{
    public function run()
    {
        $model = model('FaltaProducto');

        $model->truncate();

        $falta = new Falta();
        $faltas = count($falta->findAll());

        $producto = new Producto();
        $productos = count($producto->findAll());

        for($i=0; $i<100; $i++){
            $model->insert([
                'falta_id' => static::faker()->numberBetween(1,$faltas),
                'producto_id' => static::faker()->unique()->numberBetween(1,$productos),
                'cantidad' => static::faker()->numberBetween(0, 200)
            ]);
        }

    }
}
