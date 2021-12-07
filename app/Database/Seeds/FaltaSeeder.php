<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

use App\Models\Centro;
use App\Models\User;

class FaltaSeeder extends Seeder
{
    public function run()
    {
        $model = model('Falta');

        $model->truncate();

        $centro = new Centro();
        $centros = count($centro->findAll());

        $usuarios = new User();
        $usuarios = count($usuarios->where('type',2)->findAll());

        for($i=0; $i<50; $i++){
            $model->insert([
                'centro_id' => static::faker()->numberBetween(1,$centros),
                'solicitante_id' => static::faker()->numberBetween(1,$usuarios)
            ]);
        }

    }
}
