<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Falta extends Migration
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
            'centro_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true
            ],
            'solicitante_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp', 
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey(
            'centro_id',
            'centros',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->forge->addForeignKey(
            'solicitante_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->forge->createTable('faltas');
    }

    public function down()
    {
        //
    }
}
