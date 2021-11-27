<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        $model = model('Producto');

        $model->truncate();
        
        for($i=0;$i<300;$i++){
            $model->insert([
                'codigo' => 10000+$i,
                'descripcion' => static::faker()->sentence(6, true)
            ]);
        }
    }
}
