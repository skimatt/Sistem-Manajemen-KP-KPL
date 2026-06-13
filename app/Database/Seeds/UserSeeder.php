<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Get study program Informatika for profile association
        $prodiIf = $this->db->table('study_programs')->where('code', 'IF')->get()->getRow();
        $studyProgramId = $prodiIf ? $prodiIf->id : null;

        $passwordHash = password_hash('password', PASSWORD_DEFAULT);

        // --- 1. Seed ADMIN ---
        $adminUserId = $this->insertUser([
            'uuid'          => $this->generateUuid(),
            'name'          => 'Admin SIM KP/KPL',
            'email'         => 'admin@unmuslim.ac.id',
            'password_hash' => $passwordHash,
            'role'          => 'admin',
            'phone'         => '081122334401',
            'status'        => 'active',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        // --- 2. Seed KOORDINATOR ---
        $koordinatorUserId = $this->insertUser([
            'uuid'          => $this->generateUuid(),
            'name'          => 'Koordinator FIKOM',
            'email'         => 'koordinator@unmuslim.ac.id',
            'password_hash' => $passwordHash,
            'role'          => 'koordinator',
            'phone'         => '081122334402',
            'status'        => 'active',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        // --- 3. Seed MAHASISWA & Student Profile ---
        $mahasiswaUserId = $this->insertUser([
            'uuid'          => $this->generateUuid(),
            'name'          => 'Rahmat Mulia',
            'email'         => 'mahasiswa@unmuslim.ac.id',
            'password_hash' => $passwordHash,
            'role'          => 'mahasiswa',
            'phone'         => '081122334403',
            'status'        => 'active',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        if ($this->db->table('student_profiles')->where('user_id', $mahasiswaUserId)->countAllResults() === 0) {
            $this->db->table('student_profiles')->insert([
                'user_id'          => $mahasiswaUserId,
                'npm'              => '235520110141',
                'full_name'        => 'Rahmat Mulia',
                'birth_place'      => 'Bireuen',
                'birth_date'       => '2004-05-12',
                'gender'           => 'L',
                'religion'         => 'Islam',
                'address'          => 'Jl. Medan-Banda Aceh, Matang Glumpang Dua',
                'district'         => 'Peusangan',
                'city'             => 'Bireuen',
                'province'         => 'Aceh',
                'phone'            => '081122334403',
                'parent_name'      => 'Ahmad',
                'parent_phone'     => '081122334499',
                'study_program_id' => $studyProgramId,
                'generation_year'  => 2023,
                'current_semester' => 6,
                'profile_status'   => 'complete',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ]);
        }

        // --- 4. Seed DOSEN & Lecturer Profile ---
        $dosenUserId = $this->insertUser([
            'uuid'          => $this->generateUuid(),
            'name'          => 'Dr. Khairil, M.Kom.',
            'email'         => 'dosen@unmuslim.ac.id',
            'password_hash' => $passwordHash,
            'role'          => 'dosen',
            'phone'         => '081122334404',
            'status'        => 'active',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        if ($this->db->table('lecturer_profiles')->where('user_id', $dosenUserId)->countAllResults() === 0) {
            $this->db->table('lecturer_profiles')->insert([
                'user_id'               => $dosenUserId,
                'nidn'                  => '0102030405',
                'full_name'             => 'Dr. Khairil, M.Kom.',
                'study_program_id'      => $studyProgramId,
                'expertise'             => 'Rekayasa Perangkat Lunak',
                'max_supervision_quota' => 10,
                'is_available'          => 1,
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ]);
        }

        // --- 5. Seed INSTANSI & Institution Profile ---
        $instansiUserId = $this->insertUser([
            'uuid'          => $this->generateUuid(),
            'name'          => 'PT. Teknologi Nusantara',
            'email'         => 'instansi@technology.com',
            'password_hash' => $passwordHash,
            'role'          => 'instansi',
            'phone'         => '081122334405',
            'status'        => 'active',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        if ($this->db->table('institution_profiles')->where('user_id', $instansiUserId)->countAllResults() === 0) {
            $this->db->table('institution_profiles')->insert([
                'user_id'            => $instansiUserId,
                'uuid'               => $this->generateUuid(),
                'name'               => 'PT. Teknologi Nusantara',
                'type'               => 'mitra',
                'field_category'     => 'Software Development & IT Services',
                'address'            => 'Jl. Sudirman No. 12, Banda Aceh',
                'district'           => 'Baiturrahman',
                'city'               => 'Banda Aceh',
                'province'           => 'Aceh',
                'contact_person'     => 'Budi Santoso',
                'contact_position'   => 'HRD Manager',
                'contact_phone'      => '081122334405',
                'contact_email'      => 'hrd@technology.com',
                'partnership_status' => 'active',
                'has_account'        => 1,
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ]);
        }
    }

    private function insertUser(array $data): int
    {
        $existing = $this->db->table('users')->where('email', $data['email'])->get()->getRow();
        if ($existing) {
            return $existing->id;
        }
        $this->db->table('users')->insert($data);
        return $this->db->insertID();
    }

    private function generateUuid(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
