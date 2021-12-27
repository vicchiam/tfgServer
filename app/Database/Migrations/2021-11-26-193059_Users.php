<?php

/*
    Run 
    php spark migrate
    Create file
    php spark make:migration (name) 
*/

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
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
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 200
            ],
            'type' => [
                'type' => 'TINYINT',
                'constraint' => 1
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
        $this->forge->createTable('users');
    }

    public function down()
    {
        //
    }
}
