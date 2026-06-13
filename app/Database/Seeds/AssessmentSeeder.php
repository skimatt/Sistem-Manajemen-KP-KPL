<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // 1. Seed Dosen Template
        $existingDosen = $db->table('assessment_templates')
            ->where('assessment_type', 'dosen')
            ->where('period_id', null)
            ->get()
            ->getRow();

        if (!$existingDosen) {
            $db->table('assessment_templates')->insert([
                'period_id'       => null,
                'name'            => 'Template Penilaian Default Dosen Pembimbing',
                'assessment_type' => 'dosen',
                'version'         => 1,
                'status'          => 'active',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ]);
            $dosenTemplateId = $db->insertID();

            // Insert Dosen Components
            $db->table('assessment_components')->insertBatch([
                [
                    'assessment_template_id' => $dosenTemplateId,
                    'component_name'         => 'Penyusunan & Penulisan Laporan',
                    'max_score'              => 100.00,
                    'weight'                 => 30.00,
                    'sort_order'             => 1,
                    'status'                 => 'active',
                    'created_at'             => date('Y-m-d H:i:s'),
                    'updated_at'             => date('Y-m-d H:i:s'),
                ],
                [
                    'assessment_template_id' => $dosenTemplateId,
                    'component_name'         => 'Penguasaan Materi / Ujian Akhir',
                    'max_score'              => 100.00,
                    'weight'                 => 50.00,
                    'sort_order'             => 2,
                    'status'                 => 'active',
                    'created_at'             => date('Y-m-d H:i:s'),
                    'updated_at'             => date('Y-m-d H:i:s'),
                ],
                [
                    'assessment_template_id' => $dosenTemplateId,
                    'component_name'         => 'Etika & Sikap Akademik',
                    'max_score'              => 100.00,
                    'weight'                 => 20.00,
                    'sort_order'             => 3,
                    'status'                 => 'active',
                    'created_at'             => date('Y-m-d H:i:s'),
                    'updated_at'             => date('Y-m-d H:i:s'),
                ]
            ]);
        }

        // 2. Seed Instansi Template
        $existingInstansi = $db->table('assessment_templates')
            ->where('assessment_type', 'instansi')
            ->where('period_id', null)
            ->get()
            ->getRow();

        if (!$existingInstansi) {
            $db->table('assessment_templates')->insert([
                'period_id'       => null,
                'name'            => 'Template Penilaian Default Instansi Mitra',
                'assessment_type' => 'instansi',
                'version'         => 1,
                'status'          => 'active',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ]);
            $instansiTemplateId = $db->insertID();

            // Insert Instansi Components
            $db->table('assessment_components')->insertBatch([
                [
                    'assessment_template_id' => $instansiTemplateId,
                    'component_name'         => 'Kemampuan Teknis & Hasil Kerja',
                    'max_score'              => 100.00,
                    'weight'                 => 40.00,
                    'sort_order'             => 1,
                    'status'                 => 'active',
                    'created_at'             => date('Y-m-d H:i:s'),
                    'updated_at'             => date('Y-m-d H:i:s'),
                ],
                [
                    'assessment_template_id' => $instansiTemplateId,
                    'component_name'         => 'Kedisiplinan & Kehadiran',
                    'max_score'              => 100.00,
                    'weight'                 => 30.00,
                    'sort_order'             => 2,
                    'status'                 => 'active',
                    'created_at'             => date('Y-m-d H:i:s'),
                    'updated_at'             => date('Y-m-d H:i:s'),
                ],
                [
                    'assessment_template_id' => $instansiTemplateId,
                    'component_name'         => 'Etika, Sikap & Kerjasama',
                    'max_score'              => 100.00,
                    'weight'                 => 30.00,
                    'sort_order'             => 3,
                    'status'                 => 'active',
                    'created_at'             => date('Y-m-d H:i:s'),
                    'updated_at'             => date('Y-m-d H:i:s'),
                ]
            ]);
        }
    }
}
