<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

use App\Models\Centro;

class InstalacionSeeder extends Seeder
{
    public function run()
    {
        $model = model('Instalacion');

        $model->truncate();

        $centro = new Centro();
        $centros = count($centro->findAll());

        for($i=0;$i<150;$i++){
            $model->insert([
                'codigo' => 100+$i,
                'descripcion' => static::faker()->sentence(2, true),
                'centro_id' => static::faker()->numberBetween(1,$centros)
            ]);
        }
    }
}
