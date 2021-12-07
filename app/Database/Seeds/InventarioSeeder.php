<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

use App\Models\Centro;
use App\Models\Producto;

class InventarioSeeder extends Seeder
{
    public function run()
    {
        $model = model('Inventario');

        $model->truncate();

        $centro = new Centro();
        $centros = count($centro->findAll());

        $producto = new Producto();
        $productos = count($producto->findAll());

        for($i=0; $i<200; $i++){
            $model->insert([
                'centro_id' => static::faker()->numberBetween(1,$centros),
                'producto_id' => static::faker()->unique()->numberBetween(1,$productos),
                'cantidad' => static::faker()->numberBetween(0, 200),
                'valor' => static::faker()->randomFloat(2, $min = 0.1, $max = 2000)
            ]);
        }
    }
}
