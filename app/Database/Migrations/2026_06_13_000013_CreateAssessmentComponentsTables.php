<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAssessmentComponentsTables extends Migration
{
    public function up()
    {
        // 1. assessment_templates
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'period_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
            ],
            'assessment_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
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
        $this->forge->addForeignKey('period_id', 'kp_periods', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('assessment_templates');

        // 2. assessment_components
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'assessment_template_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'component_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
            ],
            'max_score' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => false,
            ],
            'weight' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
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
        $this->forge->addForeignKey('assessment_template_id', 'assessment_templates', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('assessment_components');
    }

    public function down()
    {
        $this->forge->dropTable('assessment_components', true);
        $this->forge->dropTable('assessment_templates', true);
    }
}
