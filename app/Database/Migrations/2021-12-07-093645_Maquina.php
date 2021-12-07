<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Maquina extends Migration
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
            'descripcion' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'unique' => true
            ],
            'ubicacion_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp', 
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey(
            'ubicacion_id',
            'ubicaciones',
            'id',
            'CASCADE',
            'CASCADE'
        );        
        $this->forge->createTable('maquinas');
    }

    public function down()
    {
        //
    }
}
