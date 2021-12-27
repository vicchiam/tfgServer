<?php

/*
    Run seeder
    php spark db:seed DatabaseSeeder 
    Make seeder
    php spark make:seeder (name) --suffix
*/

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

use App\Models\Centro;

class UserSeeder extends Seeder
{
    public function run()
    {
        $model = model('User');

        $model->truncate();

        $centro = new Centro();
        $centros = count($centro->findAll());

        $password = password_hash('pass123word',PASSWORD_DEFAULT);

        $model->insert([
            'centro_id' => 1,
            'username' => 'admin',
            'name' => 'Administrador',
            'email' => 'administrador@tfg.com',
            'password' => $password,
            'type' => 1
        ]);

        $model->insert([
            'centro_id' => 2,
            'username' => 'pepe',
            'name' => 'Pepe',
            'email' => 'pepe@tfg.com',
            'password' => $password,
            'type' => 2
        ]);

        $model->insert([
            'centro_id' => 3,
            'username' => 'eva',
            'name' => 'Eva',
            'email' => 'eva@tfg.com',
            'password' => $password,
            'type' => 3
        ]);

        for($i=0;$i<10;$i++){
            $model->insert([
                'centro_id' => static::faker()->numberBetween(1,$centros),
                'username' => static::faker()->unique()->userName,
                'name' => static::faker()->name,
                'email' => static::faker()->safeEmail,
                'password' => $password,
                'type' => static::faker()->numberBetween(1,2)
            ]);
        }

    }
}
