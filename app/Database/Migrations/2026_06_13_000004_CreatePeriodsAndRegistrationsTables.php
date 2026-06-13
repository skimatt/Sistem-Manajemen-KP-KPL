<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePeriodsAndRegistrationsTables extends Migration
{
    public function up()
    {
        // --- 1. Table: kp_periods ---
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
            'study_program_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
            ],
            'academic_year' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => false,
            ],
            'semester' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => false,
            ],
            'activity_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => false,
            ],
            'registration_start' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'registration_end' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'activity_start' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'activity_end' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'default'    => 'draft',
                'null'       => false,
            ],
            'created_by' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
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
        $this->forge->addKey('study_program_id');
        $this->forge->addKey('status');
        $this->forge->addKey('academic_year');
        $this->forge->addForeignKey('study_program_id', 'study_programs', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('kp_periods');

        // --- 2. Table: kp_registrations ---
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
            'period_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'student_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'current_status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'draft',
                'null'       => false,
            ],
            'academic_sks' => [
                'type' => 'INT',
                'null' => false,
            ],
            'academic_gpa' => [
                'type'       => 'DECIMAL',
                'constraint' => '3,2',
                'null'       => false,
            ],
            'is_gpa_eligible' => [
                'type'    => 'TINYINT',
                'default' => 0,
                'null'    => false,
            ],
            'passed_basic_programming' => [
                'type'    => 'TINYINT',
                'default' => 0,
                'null'    => false,
            ],
            'passed_data_structure' => [
                'type'    => 'TINYINT',
                'default' => 0,
                'null'    => false,
            ],
            'passed_database' => [
                'type'    => 'TINYINT',
                'default' => 0,
                'null'    => false,
            ],
            'passed_system_analysis' => [
                'type'    => 'TINYINT',
                'default' => 0,
                'null'    => false,
            ],
            'passed_networking' => [
                'type'    => 'TINYINT',
                'default' => 0,
                'null'    => false,
            ],
            'passed_concentration_course' => [
                'type'    => 'TINYINT',
                'default' => 0,
                'null'    => false,
            ],
            'education_payment_status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'academic_advisor_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'advisor_recommendation_status' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => true,
            ],
            'submitted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'verified_by' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'final_note' => [
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
        $this->forge->addUniqueKey(['period_id', 'student_id']);
        $this->forge->addKey('current_status');
        $this->forge->addForeignKey('period_id', 'kp_periods', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('student_id', 'student_profiles', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('verified_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('kp_registrations');

        // --- 3. Table: registration_status_logs ---
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
            'old_status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'new_status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'changed_by' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'changed_by_role' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('registration_id');
        $this->forge->addForeignKey('registration_id', 'kp_registrations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('changed_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('registration_status_logs');
    }

    public function down()
    {
        $this->forge->dropTable('registration_status_logs', true);
        $this->forge->dropTable('kp_registrations', true);
        $this->forge->dropTable('kp_periods', true);
    }
}
