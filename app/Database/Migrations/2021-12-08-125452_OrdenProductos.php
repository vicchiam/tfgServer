<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OrdenProductos extends Migration
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
            'orden_id' => [
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
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp', 
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey(
            'orden_id',
            'ordenes',
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
        $this->forge->createTable('orden_productos');
    }

    public function down()
    {
        //
    }
}
