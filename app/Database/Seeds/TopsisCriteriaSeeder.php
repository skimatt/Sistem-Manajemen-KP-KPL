<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TopsisCriteriaSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        $criteria = [
            [
                'code'        => 'C1',
                'name'        => 'Kesesuaian Bidang Instansi',
                'type'        => 'benefit',
                'description' => 'Tingkat kesesuaian antara program studi/kurikulum dengan bidang utama operasional instansi mitra.',
                'status'      => 'active',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'code'        => 'C2',
                'name'        => 'Kesesuaian Kemampuan Mahasiswa',
                'type'        => 'benefit',
                'description' => 'Kesesuaian spesifikasi keahlian/skill set mahasiswa dengan kebutuhan proyek instansi.',
                'status'      => 'active',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'code'        => 'C3',
                'name'        => 'Ketersediaan Kuota Penempatan',
                'type'        => 'benefit',
                'description' => 'Jumlah kapasitas penerimaan mahasiswa yang dialokasikan oleh instansi mitra.',
                'status'      => 'active',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'code'        => 'C4',
                'name'        => 'Jarak Lokasi Instansi',
                'type'        => 'cost',
                'description' => 'Jarak geografis atau aksesibilitas dari kampus ke lokasi fisik instansi.',
                'status'      => 'active',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'code'        => 'C5',
                'name'        => 'Status Kemitraan / Kerja Sama',
                'type'        => 'benefit',
                'description' => 'Tingkat keaktifan MoU/MoA kemitraan serta riwayat kolaborasi sebelumnya.',
                'status'      => 'active',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'code'        => 'C6',
                'name'        => 'Ketersediaan Pembimbing Lapangan',
                'type'        => 'benefit',
                'description' => 'Adanya staf ahli yang bersedia mendampingi dan memberikan nilai instansi di lokasi.',
                'status'      => 'active',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($criteria as $item) {
            $existing = $db->table('topsis_criteria')->where('code', $item['code'])->get()->getRow();
            if (!$existing) {
                $db->table('topsis_criteria')->insert($item);
            }
        }
    }
}
