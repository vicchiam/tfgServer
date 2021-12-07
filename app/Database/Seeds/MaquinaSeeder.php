<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

use App\Models\Ubicacion;

class MaquinaSeeder extends Seeder
{
    
    public function run()
    {
        $model = model('Maquina');

        $model->truncate();

        $ubicacion = new Ubicacion();
        $ubicaciones = count($ubicacion->findAll());

        for($i=0; $i<200; $i++){
            $model->insert([
                'descripcion' => static::faker()->unique()->sentence(2, true),
                'ubicacion_id' => static::faker()->numberBetween(1,$ubicaciones)
            ]);
        }
    }

}
