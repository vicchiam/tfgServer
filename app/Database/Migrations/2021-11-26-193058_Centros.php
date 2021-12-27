<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Centros extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],
            'direccion' => [
                'type' => 'TEXT',
                'null' => true
            ],            
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp', 
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('centros');
    }

    public function down()
    {
        //
    }
}
