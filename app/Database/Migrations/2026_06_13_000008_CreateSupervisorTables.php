<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSupervisorTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'uuid' => [
                'type'       => 'CHAR',
                'constraint' => '36',
                'null'       => false,
            ],
            'registration_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'lecturer_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'assigned_by' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'assigned_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'default'    => 'active',
                'null'       => false,
            ],
            'note' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('uuid');
        $this->forge->addKey('registration_id');
        $this->forge->addKey('lecturer_id');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('registration_id', 'kp_registrations', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('lecturer_id', 'lecturer_profiles', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('assigned_by', 'users', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('supervisor_assignments');
    }

    public function down()
    {
        $this->forge->dropTable('supervisor_assignments', true);
    }
}
