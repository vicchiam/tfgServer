<?php

/*
    Run seeder
    php spark db:seed DatabaseSeeder 
    Make seeder
    php spark make:seeder (name) --suffix
*/

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $model = model('User');

        $model->truncate();

        $password = password_hash('pass123word',PASSWORD_DEFAULT);

        $model->insert([
            'username' => 'admin',
            'name' => 'Administrador',
            'email' => 'administrador@tfg.com',
            'password' => $password,
            'type' => 1
        ]);

        for($i=0;$i<10;$i++){
            $model->insert([
                'username' => static::faker()->unique()->userName,
                'name' => static::faker()->name,
                'email' => static::faker()->safeEmail,
                'password' => $password,
                'type' => static::faker()->numberBetween(1,2)
            ]);
        }

    }
}
