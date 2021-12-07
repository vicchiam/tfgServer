<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Inventario extends Migration
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
            'producto_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true
            ],
            'cantidad' => [
                'type' => 'FLOAT'
            ],
            'valor' => [
                'type' => 'FLOAT'
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
            'producto_id',
            'productos',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->forge->addUniqueKey(['centro_id', 'producto_id']);
        $this->forge->createTable('inventario');
    }

    public function down()
    {
        //
    }
}
