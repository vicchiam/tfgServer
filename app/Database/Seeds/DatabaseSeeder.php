<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this->db->disableForeignKeyChecks();
        $this->call('UserSeeder'); 
        $this->call('CentroSeeder');
        $this->call('ProductoSeeder');
        $this->call('InstalacionSeeder');
        $this->db->enableForeignKeyChecks();        
    }

}


