<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDocumentsTables extends Migration
{
    public function up()
    {
        // --- 1. Table: document_requirements ---
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
            'document_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
            ],
            'document_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => false,
            ],
            'allowed_extensions' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'default'    => 'pdf,jpg,jpeg,png',
                'null'       => false,
            ],
            'max_size_kb' => [
                'type'    => 'INT',
                'default' => 10240,
                'null'    => false,
            ],
            'is_required' => [
                'type'    => 'TINYINT',
                'default' => 1,
                'null'    => false,
            ],
            'stage' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'registrasi',
                'null'       => false,
            ],
            'sort_order' => [
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
        $this->forge->addKey('period_id');
        $this->forge->addForeignKey('period_id', 'kp_periods', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('document_requirements');

        // --- 2. Table: student_documents ---
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
            'requirement_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'uploaded_by' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'document_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
            ],
            'document_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => false,
            ],
            'original_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'stored_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => false,
            ],
            'file_ext' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => false,
            ],
            'file_size_kb' => [
                'type' => 'INT',
                'null' => false,
            ],
            'mime_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'version' => [
                'type'    => 'INT',
                'default' => 1,
                'null'    => false,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'default'    => 'menunggu_verifikasi',
                'null'       => false,
            ],
            'verified_by' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'verification_note' => [
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
        $this->forge->addKey('requirement_id');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('registration_id', 'kp_registrations', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('requirement_id', 'document_requirements', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('uploaded_by', 'users', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('verified_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('student_documents');

        // --- 3. Table: generated_documents ---
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
            'template_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'generated_by' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'document_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
            ],
            'document_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => false,
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => false,
            ],
            'version' => [
                'type'    => 'INT',
                'default' => 1,
                'null'    => false,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'default'    => 'active',
                'null'       => false,
            ],
            'generated_at' => [
                'type' => 'DATETIME',
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
        $this->forge->addForeignKey('registration_id', 'kp_registrations', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('generated_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('generated_documents');
    }

    public function down()
    {
        $this->forge->dropTable('generated_documents', true);
        $this->forge->dropTable('student_documents', true);
        $this->forge->dropTable('document_requirements', true);
    }
}
