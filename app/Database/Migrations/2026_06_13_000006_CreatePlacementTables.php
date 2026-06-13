<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePlacementTables extends Migration
{
    public function up()
    {
        // --- 1. Table: placement_requests ---
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
            'placement_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => false,
            ],
            'institution_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'proposed_institution_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
                'null'       => true,
            ],
            'proposed_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'proposed_field' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'contact_person' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'contact_position' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'contact_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => true,
            ],
            'contact_email' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'draft',
                'null'       => false,
            ],
            'submitted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'reviewed_by' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'reviewed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'review_note' => [
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
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('uuid');
        $this->forge->addKey('registration_id');
        $this->forge->addKey('placement_type');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('registration_id', 'kp_registrations', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('institution_id', 'institution_profiles', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('reviewed_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('placement_requests');

        // --- 2. Table: placement_choices ---
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'registration_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'institution_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'institution_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
                'null'       => false,
            ],
            'institution_address' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'priority_order' => [
                'type' => 'INT',
                'null' => false,
            ],
            'is_selected' => [
                'type'    => 'TINYINT',
                'default' => 0,
                'null'    => false,
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
        $this->forge->addKey('registration_id');
        $this->forge->addForeignKey('registration_id', 'kp_registrations', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('institution_id', 'institution_profiles', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('placement_choices');

        // --- 3. Table: institution_quotas ---
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'period_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'institution_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'quota_total' => [
                'type'    => 'INT',
                'default' => 0,
                'null'    => false,
            ],
            'quota_used' => [
                'type'    => 'INT',
                'default' => 0,
                'null'    => false,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'default'    => 'active',
                'null'       => false,
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
        $this->forge->addUniqueKey(['period_id', 'institution_id']);
        $this->forge->addForeignKey('period_id', 'kp_periods', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('institution_id', 'institution_profiles', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('institution_quotas');
    }

    public function down()
    {
        $this->forge->dropTable('institution_quotas', true);
        $this->forge->dropTable('placement_choices', true);
        $this->forge->dropTable('placement_requests', true);
    }
}
