<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

use App\Models\Orden;
use App\Models\User;

class OrdenTecnicosSeeder extends Seeder
{
    public function run()
    {
        $model = model('OrdenTecnico');

        $model->truncate();

        $orden = new Orden();
        $ordenes = count($orden->findAll());

        $user = new User();
        $users = count($user->findAll());

        for($i=0; $i<100; $i++){
            $model->insert([
                'orden_id' => static::faker()->numberBetween(1,$ordenes), 
                'user_id' => static::faker()->numberBetween(1,$users),
                'fecha' => static::faker()->dateTimeThisYear('+ 2 month')->format('Y-m-d'),
                'minutos' => static::faker()->numberBetween(0, 480)
            ]);
        }
    }
}
