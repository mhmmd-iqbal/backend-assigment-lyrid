<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableUsers extends Migration
{
    public function up()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'email'      => ['type' => 'varchar', 'constraint' => 31, 'unique'  => true],
            'uid'        => ['type' => 'varchar', 'constraint' => 31],
            'role'       => ['type' => 'enum', 'constraint' => ['admin', 'employee'], 'default' => 'admin'],
            'password'   => ['type' => 'varchar', 'constraint' => 225],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],
        ]);

        $this->forge->addKey('uid');
        $this->forge->addKey(['deleted_at', 'id']);
        $this->forge->addKey('created_at');

        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
