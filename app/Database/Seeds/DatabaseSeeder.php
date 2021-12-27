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
        $this->call('InventarioSeeder');
        $this->call('FaltaSeeder');
        $this->call('FaltaProductoSeeder');
        $this->call('UbicacionSeeder');
        $this->call('MaquinaSeeder');        
        
        $this->call('OrdenSeeder');        
        
        $this->call('OrdenTecnicosSeeder');        
        $this->call('OrdenProductosSeeder');
        
        $this->db->enableForeignKeyChecks();      
          
    }

}


