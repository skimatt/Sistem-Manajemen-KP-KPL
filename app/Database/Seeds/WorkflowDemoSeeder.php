<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WorkflowDemoSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $student = $this->db->table('student_profiles')->where('npm', '235520110141')->get()->getRow();
        $lecturer = $this->db->table('lecturer_profiles')->where('nidn', '0102030405')->get()->getRow();
        $institution = $this->db->table('institution_profiles')->where('user_id', 5)->get()->getRow();
        $admin = $this->db->table('users')->where('role', 'admin')->get()->getRow();
        $koordinator = $this->db->table('users')->where('role', 'koordinator')->get()->getRow();

        if (!$student || !$lecturer || !$institution || !$admin || !$koordinator) {
            throw new \RuntimeException('Data user/profile demo belum lengkap. Jalankan DatabaseSeeder terlebih dahulu.');
        }

        $period = $this->db->table('kp_periods')
            ->where('name', 'KP Informatika E2E 2026')
            ->where('deleted_at', null)
            ->get()
            ->getRow();

        if (!$period) {
            $this->db->table('kp_periods')->insert([
                'uuid' => $this->uuid(),
                'study_program_id' => $student->study_program_id,
                'name' => 'KP Informatika E2E 2026',
                'academic_year' => '2026/2027',
                'semester' => 'Genap',
                'activity_type' => 'KP',
                'registration_start' => '2026-06-01',
                'registration_end' => '2026-12-31',
                'activity_start' => '2026-07-01',
                'activity_end' => '2026-09-30',
                'status' => 'aktif',
                'created_by' => $admin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $periodId = $this->db->insertID();
        } else {
            $periodId = $period->id;
        }

        $this->seedDocumentRequirements($periodId, $now);
        $this->seedTopsisWeights($periodId, $now);
        $this->seedInstitutionQuota($periodId, $institution->id, $now);

        $registration = $this->db->table('kp_registrations')
            ->where('period_id', $periodId)
            ->where('student_id', $student->id)
            ->get()
            ->getRow();

        if (!$registration) {
            $this->db->table('kp_registrations')->insert([
                'uuid' => $this->uuid(),
                'period_id' => $periodId,
                'student_id' => $student->id,
                'current_status' => 'menunggu_validasi_akhir',
                'academic_sks' => 120,
                'academic_gpa' => 3.56,
                'is_gpa_eligible' => 1,
                'passed_basic_programming' => 1,
                'passed_data_structure' => 1,
                'passed_database' => 1,
                'passed_system_analysis' => 1,
                'passed_networking' => 1,
                'passed_concentration_course' => 1,
                'education_payment_status' => 'terbayar',
                'academic_advisor_name' => 'Dr. Khairil, M.Kom.',
                'advisor_recommendation_status' => 'layak',
                'submitted_at' => $now,
                'verified_at' => $now,
                'verified_by' => $koordinator->id,
                'final_note' => 'Data demo E2E untuk validasi workflow lintas role.',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $registrationId = $this->db->insertID();
        } else {
            $registrationId = $registration->id;
            $this->db->table('kp_registrations')->where('id', $registrationId)->update([
                'current_status' => 'menunggu_validasi_akhir',
                'updated_at' => $now,
            ]);
        }

        $this->seedRegistrationLog($registrationId, 'menunggu_validasi_akhir', $koordinator->id, $now);
        $this->seedPlacement($registrationId, $institution->id, $koordinator->id, $now);
        $this->seedSupervisor($registrationId, $lecturer->id, $koordinator->id, $now);
        $this->seedStudentDocuments($registrationId, $student->user_id, $periodId, $now);
        $this->seedGeneratedDocuments($registrationId, $koordinator->id, $now);
        $this->seedLogbook($registrationId, $lecturer->user_id, $now);
        $this->seedScores($registrationId, $lecturer->user_id, $institution->user_id, $koordinator->id, $now);
    }

    private function seedDocumentRequirements(int $periodId, string $now): void
    {
        $requirements = [
            ['Bukti Pembayaran KP/KPL', 'bukti_pembayaran', 'registrasi', 1],
            ['KHS Terbaru', 'khs_terbaru', 'registrasi', 2],
            ['Surat Rekomendasi Dosen PA', 'surat_rekomendasi_pa', 'registrasi', 3],
            ['Surat Balasan/Penerimaan Instansi', 'surat_penerimaan_instansi', 'penerimaan-instansi', 4],
            ['Laporan Akhir', 'laporan_akhir', 'laporan-akhir', 5],
        ];

        foreach ($requirements as $item) {
            $exists = $this->db->table('document_requirements')
                ->where('period_id', $periodId)
                ->where('document_code', $item[1])
                ->countAllResults();

            if ($exists === 0) {
                $this->db->table('document_requirements')->insert([
                    'period_id' => $periodId,
                    'document_name' => $item[0],
                    'document_code' => $item[1],
                    'allowed_extensions' => 'pdf,jpg,jpeg,png',
                    'max_size_kb' => 10240,
                    'is_required' => 1,
                    'stage' => $item[2],
                    'sort_order' => $item[3],
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    private function seedTopsisWeights(int $periodId, string $now): void
    {
        $weights = ['C1' => 25, 'C2' => 20, 'C3' => 15, 'C4' => 10, 'C5' => 15, 'C6' => 15];
        $criteria = $this->db->table('topsis_criteria')->get()->getResult();

        foreach ($criteria as $criterion) {
            $weight = $weights[$criterion->code] ?? 10;
            $exists = $this->db->table('topsis_weights')
                ->where('period_id', $periodId)
                ->where('criteria_id', $criterion->id)
                ->countAllResults();

            if ($exists === 0) {
                $this->db->table('topsis_weights')->insert([
                    'period_id' => $periodId,
                    'criteria_id' => $criterion->id,
                    'weight' => $weight,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    private function seedInstitutionQuota(int $periodId, int $institutionId, string $now): void
    {
        $exists = $this->db->table('institution_quotas')
            ->where('period_id', $periodId)
            ->where('institution_id', $institutionId)
            ->countAllResults();

        if ($exists === 0) {
            $this->db->table('institution_quotas')->insert([
                'period_id' => $periodId,
                'institution_id' => $institutionId,
                'quota_total' => 10,
                'quota_used' => 1,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    private function seedRegistrationLog(int $registrationId, string $status, int $userId, string $now): void
    {
        $exists = $this->db->table('registration_status_logs')
            ->where('registration_id', $registrationId)
            ->where('new_status', $status)
            ->countAllResults();

        if ($exists === 0) {
            $this->db->table('registration_status_logs')->insert([
                'registration_id' => $registrationId,
                'old_status' => 'draft',
                'new_status' => $status,
                'changed_by' => $userId,
                'changed_by_role' => 'koordinator',
                'note' => 'Seeder demo E2E menyiapkan status untuk pengujian lintas role.',
                'created_at' => $now,
            ]);
        }
    }

    private function seedPlacement(int $registrationId, int $institutionId, int $reviewerId, string $now): void
    {
        $exists = $this->db->table('placement_requests')
            ->where('registration_id', $registrationId)
            ->where('institution_id', $institutionId)
            ->countAllResults();

        if ($exists === 0) {
            $this->db->table('placement_requests')->insert([
                'uuid' => $this->uuid(),
                'registration_id' => $registrationId,
                'placement_type' => 'mitra',
                'institution_id' => $institutionId,
                'status' => 'diterima_instansi',
                'submitted_at' => $now,
                'reviewed_by' => $reviewerId,
                'reviewed_at' => $now,
                'review_note' => 'Penempatan demo E2E diterima oleh instansi mitra.',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    private function seedSupervisor(int $registrationId, int $lecturerId, int $assignedBy, string $now): void
    {
        $exists = $this->db->table('supervisor_assignments')
            ->where('registration_id', $registrationId)
            ->where('status', 'active')
            ->countAllResults();

        if ($exists === 0) {
            $this->db->table('supervisor_assignments')->insert([
                'uuid' => $this->uuid(),
                'registration_id' => $registrationId,
                'lecturer_id' => $lecturerId,
                'assigned_by' => $assignedBy,
                'assigned_at' => $now,
                'status' => 'active',
                'note' => 'Pembimbing demo E2E.',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    private function seedStudentDocuments(int $registrationId, int $uploadedBy, int $periodId, string $now): void
    {
        $requirements = $this->db->table('document_requirements')
            ->where('period_id', $periodId)
            ->get()
            ->getResult();

        foreach ($requirements as $requirement) {
            $exists = $this->db->table('student_documents')
                ->where('registration_id', $registrationId)
                ->where('document_code', $requirement->document_code)
                ->countAllResults();

            if ($exists === 0) {
                $this->db->table('student_documents')->insert([
                    'uuid' => $this->uuid(),
                    'registration_id' => $registrationId,
                    'requirement_id' => $requirement->id,
                    'uploaded_by' => $uploadedBy,
                    'document_name' => $requirement->document_name,
                    'document_code' => $requirement->document_code,
                    'original_name' => $requirement->document_code . '.pdf',
                    'stored_name' => $requirement->document_code . '_demo.pdf',
                    'file_path' => 'writable/uploads/demo/' . $requirement->document_code . '_demo.pdf',
                    'file_ext' => 'pdf',
                    'file_size_kb' => 1,
                    'mime_type' => 'application/pdf',
                    'version' => 1,
                    'status' => 'valid',
                    'verified_by' => 2,
                    'verified_at' => $now,
                    'verification_note' => 'Dokumen demo valid untuk uji workflow.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    private function seedGeneratedDocuments(int $registrationId, int $generatedBy, string $now): void
    {
        $documents = [
            ['Surat Permohonan KP/KPL', 'surat_permohonan'],
            ['Surat Tugas Dosen Pembimbing', 'surat_tugas_pembimbing'],
            ['Form Penilaian Instansi', 'form_penilaian_instansi'],
        ];

        foreach ($documents as $document) {
            $exists = $this->db->table('generated_documents')
                ->where('registration_id', $registrationId)
                ->where('document_code', $document[1])
                ->countAllResults();

            if ($exists === 0) {
                $this->db->table('generated_documents')->insert([
                    'uuid' => $this->uuid(),
                    'registration_id' => $registrationId,
                    'generated_by' => $generatedBy,
                    'document_name' => $document[0],
                    'document_code' => $document[1],
                    'file_path' => 'writable/generated/documents/demo/' . $document[1] . '.pdf',
                    'version' => 1,
                    'status' => 'active',
                    'generated_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    private function seedLogbook(int $registrationId, int $reviewerId, string $now): void
    {
        $week = $this->db->table('logbook_weeks')
            ->where('registration_id', $registrationId)
            ->where('week_number', 1)
            ->get()
            ->getRow();

        if (!$week) {
            $this->db->table('logbook_weeks')->insert([
                'uuid' => $this->uuid(),
                'registration_id' => $registrationId,
                'week_number' => 1,
                'start_date' => '2026-07-01',
                'end_date' => '2026-07-07',
                'weekly_target' => 'Orientasi lingkungan kerja dan analisis kebutuhan sistem.',
                'weekly_result' => 'Mahasiswa memahami SOP dan menyusun catatan kebutuhan awal.',
                'obstacle' => 'Belum ada.',
                'next_plan' => 'Mulai desain modul.',
                'status' => 'disetujui',
                'submitted_at' => $now,
                'approved_at' => $now,
                'approved_by' => $reviewerId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $weekId = $this->db->insertID();
        } else {
            $weekId = $week->id;
        }

        $entryExists = $this->db->table('logbook_daily_entries')->where('logbook_week_id', $weekId)->countAllResults();
        if ($entryExists === 0) {
            $this->db->table('logbook_daily_entries')->insert([
                'logbook_week_id' => $weekId,
                'activity_date' => '2026-07-01',
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'activity_description' => 'Orientasi proyek dan pengumpulan kebutuhan awal.',
                'result_description' => 'Draft kebutuhan sistem tersusun.',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $reviewExists = $this->db->table('logbook_reviews')
            ->where('logbook_week_id', $weekId)
            ->where('reviewed_by', $reviewerId)
            ->countAllResults();
        if ($reviewExists === 0) {
            $this->db->table('logbook_reviews')->insert([
                'logbook_week_id' => $weekId,
                'reviewed_by' => $reviewerId,
                'status' => 'disetujui',
                'comment' => 'Logbook demo disetujui.',
                'reviewed_at' => $now,
                'created_at' => $now,
            ]);
        }
    }

    private function seedScores(int $registrationId, int $lecturerUserId, ?int $institutionUserId, int $validatorId, string $now): void
    {
        $templates = $this->db->table('assessment_templates')->whereIn('assessment_type', ['dosen', 'instansi'])->get()->getResult();
        foreach ($templates as $template) {
            $components = $this->db->table('assessment_components')
                ->where('assessment_template_id', $template->id)
                ->get()
                ->getResult();

            foreach ($components as $component) {
                $exists = $this->db->table('assessment_scores')
                    ->where('registration_id', $registrationId)
                    ->where('component_id', $component->id)
                    ->where('assessor_role', $template->assessment_type)
                    ->countAllResults();

                if ($exists === 0) {
                    $this->db->table('assessment_scores')->insert([
                        'registration_id' => $registrationId,
                        'assessment_template_id' => $template->id,
                        'component_id' => $component->id,
                        'component_name' => $component->component_name,
                        'assessor_user_id' => $template->assessment_type === 'dosen' ? $lecturerUserId : $institutionUserId,
                        'assessor_role' => $template->assessment_type,
                        'score' => 86.00,
                        'note' => 'Nilai demo E2E.',
                        'status' => 'submitted',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }

        $exists = $this->db->table('final_scores')->where('registration_id', $registrationId)->countAllResults();
        if ($exists === 0) {
            $this->db->table('final_scores')->insert([
                'registration_id' => $registrationId,
                'institution_score' => 86.00,
                'lecturer_score' => 86.00,
                'admin_score' => 90.00,
                'final_score' => 86.40,
                'final_grade' => 'A',
                'weight_snapshot' => json_encode(['instansi' => 40, 'dosen' => 50, 'admin_logbook' => 10]),
                'status' => 'menunggu_validasi',
                'validated_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    private function uuid(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
