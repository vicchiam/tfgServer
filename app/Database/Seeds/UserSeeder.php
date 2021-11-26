<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $model = model('User');

        $model->truncate();

        $model->insert([
            'username' => 'admin',
            'name' => 'Administrador',
            'email' => 'administrador@tfg.com',
            'password' => static::faker()->password,
            'type' => 0
        ]);

        for($i=0;$i<10;$i++){
            $model->insert([
                'username' => static::faker()->unique()->userName,
                'name' => static::faker()->ipv4,
                'email' => static::faker()->name,
                'password' => static::faker()->password,
                'type' => static::faker()->numberBetween(0,2)
            ]);
        }

    }
}
