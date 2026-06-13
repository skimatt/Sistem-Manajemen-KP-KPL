<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTopsisTables extends Migration
{
    public function up()
    {
        // --- 1. Table: topsis_criteria ---
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('topsis_criteria');

        // --- 2. Table: topsis_weights ---
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
            'criteria_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'weight' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
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
        $this->forge->addUniqueKey(['period_id', 'criteria_id']);
        $this->forge->addForeignKey('period_id', 'kp_periods', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('criteria_id', 'topsis_criteria', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('topsis_weights');

        // --- 3. Table: topsis_scores ---
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
                'null'     => false,
            ],
            'criteria_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'score' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,4',
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
        $this->forge->addForeignKey('registration_id', 'kp_registrations', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('institution_id', 'institution_profiles', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('criteria_id', 'topsis_criteria', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('topsis_scores');

        // --- 4. Table: topsis_results ---
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
                'null'     => false,
            ],
            'preference_value' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,6',
                'null'       => false,
            ],
            'rank_order' => [
                'type' => 'INT',
                'null' => false,
            ],
            'calculation_snapshot' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'calculated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('registration_id', 'kp_registrations', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('institution_id', 'institution_profiles', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('topsis_results');
    }

    public function down()
    {
        $this->forge->dropTable('topsis_results', true);
        $this->forge->dropTable('topsis_scores', true);
        $this->forge->dropTable('topsis_weights', true);
        $this->forge->dropTable('topsis_criteria', true);
    }
}
