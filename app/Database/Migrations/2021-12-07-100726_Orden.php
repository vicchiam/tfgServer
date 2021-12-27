<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Orden extends Migration
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
            'tipo' => [
                'type' => 'INT',
                'constraint' => 1
            ],
            'solicitante_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true
            ],
            'centro_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true
            ],
            'ubicacion_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true
            ],
            'maq_inst' => [
                'type' => 'INT',
                'constraint' => 1
            ],
            'maquina_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => true
            ],
            'instalacion_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => true
            ],
            'averia' => [
                'type' => 'TEXT'
            ],
            'trabajo' => [
                'type' => 'TEXT'
            ],
            'fecha_inicio' => [
                'type' => 'DATE',
                'null' => true
            ],
            'fecha_fin' => [
                'type' => 'DATE',
                'null' => true
            ],
            'parada' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true
            ],
            'estado' => [
                'type' => 'INT',
                'constraint' => 2
            ],
            'razon' => [
                'type' => 'TEXT'
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp', 
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey(
            'solicitante_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        ); 
        $this->forge->addForeignKey(
            'centro_id',
            'centros',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->forge->addForeignKey(
            'ubicacion_id',
            'ubicaciones',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->forge->addForeignKey(
            'maquina_id',
            'maquinas',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->forge->addForeignKey(
            'instalacion_id',
            'instalaciones',
            'id',
            'CASCADE',
            'CASCADE'
        );  
        $this->forge->createTable('ordenes');
    }

    public function down()
    {
        //
    }
}
