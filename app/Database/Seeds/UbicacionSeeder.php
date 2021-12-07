<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

use App\Models\Centro;

class UbicacionSeeder extends Seeder
{
    public function run()
    {
        $model = model('Ubicacion');

        $model->truncate();

        $centro = new Centro();
        $centros = count($centro->findAll());

        for($i=0; $i<50; $i++){
            $model->insert([
                'descripcion' => static::faker()->unique()->sentence(2, true),
                'centro_id' => static::faker()->numberBetween(1,$centros)
            ]);
        }
    }
}
