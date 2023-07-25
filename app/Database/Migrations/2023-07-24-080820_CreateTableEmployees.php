<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableEmployees extends Migration
{
    public function up()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'email'      => ['type' => 'varchar', 'constraint' => 31, 'unique' => true],
            'name'       => ['type' => 'varchar', 'constraint' => 50],
            'gender'     => ['type' => 'enum', 'constraint' => ['male', 'female'], 'default' => 'male'],
            'phone'      => ['type' => 'varchar', 'constraint' => 20],
            'photo'      => ['type' => 'varchar', 'constraint' => 225, 'null' => true],
            'uid'        => ['type' => 'varchar', 'constraint' => 31],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],
        ]);

        $this->forge->addKey('uid');
        $this->forge->addKey(['deleted_at', 'id']);
        $this->forge->addKey('created_at');

        $this->forge->createTable('employees');
    }

    public function down()
    {
        $this->forge->dropTable('employees');
    }
}
