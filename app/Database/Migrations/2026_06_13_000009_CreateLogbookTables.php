<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLogbookTables extends Migration
{
    public function up()
    {
        // --- 1. Table: logbook_weeks ---
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
            'week_number' => [
                'type' => 'INT',
                'null' => false,
            ],
            'start_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'end_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'weekly_target' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'weekly_result' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'obstacle' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'next_plan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'default'    => 'draft',
                'null'       => false,
            ],
            'submitted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'approved_by' => [
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
        $this->forge->addUniqueKey(['registration_id', 'week_number']);
        $this->forge->addKey('status');
        $this->forge->addForeignKey('registration_id', 'kp_registrations', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('logbook_weeks');

        // --- 2. Table: logbook_daily_entries ---
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'logbook_week_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'activity_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'start_time' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'end_time' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'activity_description' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'result_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'documentation_file_id' => [
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('logbook_week_id');
        $this->forge->addForeignKey('logbook_week_id', 'logbook_weeks', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('documentation_file_id', 'student_documents', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('logbook_daily_entries');

        // --- 3. Table: logbook_reviews ---
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'logbook_week_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'reviewed_by' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => false,
            ],
            'comment' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'reviewed_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('logbook_week_id');
        $this->forge->addForeignKey('logbook_week_id', 'logbook_weeks', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('reviewed_by', 'users', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('logbook_reviews');
    }

    public function down()
    {
        $this->forge->dropTable('logbook_reviews', true);
        $this->forge->dropTable('logbook_daily_entries', true);
        $this->forge->dropTable('logbook_weeks', true);
    }
}
