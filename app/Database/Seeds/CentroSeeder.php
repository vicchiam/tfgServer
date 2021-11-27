<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CentroSeeder extends Seeder
{
    public function run()
    {
        $model = model('Centro');

        $model->truncate();

        $model->insert([
            'nombre' => 'Picassent',
            'direccion' => static::faker()->address
        ]);

        $model->insert([
            'nombre' => 'Merca',
            'direccion' => static::faker()->address
        ]);

        $model->insert([
            'nombre' => 'Teruel',
            'direccion' => static::faker()->address
        ]);

    }
}
