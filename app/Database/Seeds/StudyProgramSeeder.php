<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StudyProgramSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'code'       => 'IF',
                'name'       => 'Informatika',
                'faculty'    => 'Fakultas Ilmu Komputer',
                'kp_label'   => 'KP',
                'status'     => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code'       => 'IM',
                'name'       => 'Informatika Medis',
                'faculty'    => 'Fakultas Ilmu Komputer',
                'kp_label'   => 'KPL',
                'status'     => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        foreach ($data as $row) {
            if ($this->db->table('study_programs')->where('code', $row['code'])->countAllResults() === 0) {
                $this->db->table('study_programs')->insert($row);
            }
        }
    }
}
