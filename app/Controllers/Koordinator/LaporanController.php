<?php

namespace App\Controllers\Koordinator;

use App\Controllers\BaseController;
use App\Models\PeriodModel;
use App\Models\RegistrationModel;
use App\Services\AuditService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

class LaporanController extends BaseController
{
    protected $periodModel;
    protected $registrationModel;

    public function __construct()
    {
        $this->periodModel       = new PeriodModel();
        $this->registrationModel = new RegistrationModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        // Fetch periods list
        $periods = $this->periodModel->where('deleted_at', null)->orderBy('id', 'DESC')->findAll();
        $selectedPeriodId = $this->request->getVar('period_id');
        if (empty($selectedPeriodId)) {
            $activePeriod = $this->periodModel->where('status', 'aktif')->first();
            $selectedPeriodId = $activePeriod ? $activePeriod['id'] : ($periods ? $periods[0]['id'] : null);
        }

        $stats = [
            'total_students'  => 0,
            'completed_count' => 0,
            'active_count'    => 0,
            'failed_count'    => 0,
            'avg_score'       => 0,
            'grade_dist'      => [
                'A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0, 'Lainnya' => 0
            ]
        ];

        $studentsList = [];

        if ($selectedPeriodId) {
            // Calculate aggregate statistics
            $stats['total_students'] = $db->table('kp_registrations')
                ->where('period_id', $selectedPeriodId)
                ->where('deleted_at', null)
                ->countAllResults();

            $stats['completed_count'] = $db->table('kp_registrations')
                ->where('period_id', $selectedPeriodId)
                ->whereIn('current_status', ['selesai', 'diarsipkan'])
                ->where('deleted_at', null)
                ->countAllResults();

            $stats['active_count'] = $db->table('kp_registrations')
                ->where('period_id', $selectedPeriodId)
                ->whereNotIn('current_status', ['draft', 'registrasi_ditolak', 'penempatan_ditolak', 'selesai', 'diarsipkan'])
                ->where('deleted_at', null)
                ->countAllResults();

            $stats['failed_count'] = $db->table('kp_registrations')
                ->where('period_id', $selectedPeriodId)
                ->whereIn('current_status', ['registrasi_ditolak', 'penempatan_ditolak'])
                ->where('deleted_at', null)
                ->countAllResults();

            // Calculate average final score
            $avgRow = $db->table('final_scores')
                ->selectAvg('final_score')
                ->join('kp_registrations', 'kp_registrations.id = final_scores.registration_id')
                ->where('kp_registrations.period_id', $selectedPeriodId)
                ->where('kp_registrations.deleted_at', null)
                ->get()
                ->getRowArray();
            $stats['avg_score'] = $avgRow['final_score'] !== null ? floatval($avgRow['final_score']) : 0;

            // Fetch grade distribution
            $grades = $db->table('final_scores')
                ->select('final_grade, COUNT(*) as qty')
                ->join('kp_registrations', 'kp_registrations.id = final_scores.registration_id')
                ->where('kp_registrations.period_id', $selectedPeriodId)
                ->where('kp_registrations.deleted_at', null)
                ->groupBy('final_grade')
                ->get()
                ->getResultArray();

            foreach ($grades as $g) {
                $gradeLetter = strtoupper(trim($g['final_grade']));
                if (array_key_exists($gradeLetter, $stats['grade_dist'])) {
                    $stats['grade_dist'][$gradeLetter] = intval($g['qty']);
                } elseif (!empty($gradeLetter)) {
                    $stats['grade_dist']['Lainnya'] += intval($g['qty']);
                }
            }

            // Fetch students list for preview
            $studentsList = $db->table('kp_registrations')
                ->select('kp_registrations.*, student_profiles.npm, student_profiles.full_name, study_programs.name as prodi_name,
                          final_scores.final_score, final_scores.final_grade')
                ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
                ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
                ->join('final_scores', 'final_scores.registration_id = kp_registrations.id', 'left')
                ->where('kp_registrations.period_id', $selectedPeriodId)
                ->where('kp_registrations.deleted_at', null)
                ->get()
                ->getResultArray();
        }

        $data = [
            'title'            => 'Laporan & Rekapitulasi Kegiatan',
            'periods'          => $periods,
            'selectedPeriodId' => $selectedPeriodId,
            'stats'            => $stats,
            'students'         => $studentsList,
        ];

        return view('koordinator/manajemen/laporan/index', $data);
    }

    public function exportExcel()
    {
        $periodId = $this->request->getVar('period_id');
        if (empty($periodId)) {
            return redirect()->to(base_url('koordinator/laporan'))->with('error', 'Silakan pilih periode terlebih dahulu.');
        }

        $period = $this->periodModel->find($periodId);
        if (!$period) {
            return redirect()->to(base_url('koordinator/laporan'))->with('error', 'Periode tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $students = $db->table('kp_registrations')
            ->select('kp_registrations.*, student_profiles.npm, student_profiles.full_name, study_programs.name as prodi_name,
                      institution_profiles.name as institution_name,
                      final_scores.institution_score, final_scores.lecturer_score, final_scores.admin_score, final_scores.final_score, final_scores.final_grade')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
            ->join('final_scores', 'final_scores.registration_id = kp_registrations.id', 'left')
            ->join('placement_requests', 'placement_requests.registration_id = kp_registrations.id AND placement_requests.status = "disetujui"', 'left')
            ->join('institution_profiles', 'institution_profiles.id = placement_requests.institution_id', 'left')
            ->where('kp_registrations.period_id', $periodId)
            ->where('kp_registrations.deleted_at', null)
            ->get()
            ->getResultArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Title Block
        $sheet->setCellValue('A1', 'LAPORAN REKAPITULASI MAHASISWA KP/KPL');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        
        $sheet->setCellValue('A2', 'Periode: ' . $period['name']);
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A2')->getFont()->setItalic(true);

        // Header Columns
        $headers = [
            'A4' => 'NPM',
            'B4' => 'Nama Lengkap',
            'C4' => 'Program Studi',
            'D4' => 'Instansi Tempat',
            'E4' => 'Status Tahap',
            'F4' => 'Nilai Instansi',
            'G4' => 'Nilai Dosen',
            'H4' => 'Nilai Admin',
            'I4' => 'Nilai Akhir',
            'J4' => 'Grade',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }

        // Data Rows
        $row = 5;
        foreach ($students as $s) {
            $sheet->setCellValue('A' . $row, $s['npm']);
            $sheet->setCellValue('B' . $row, $s['full_name']);
            $sheet->setCellValue('C' . $row, $s['prodi_name'] ?? '-');
            $sheet->setCellValue('D' . $row, $s['institution_name'] ?? 'Mandiri / Belum Diplot');
            $sheet->setCellValue('E' . $row, ucwords(str_replace('_', ' ', $s['current_status'])));
            $sheet->setCellValue('F' . $row, $s['institution_score'] !== null ? number_format($s['institution_score'], 2) : '-');
            $sheet->setCellValue('G' . $row, $s['lecturer_score'] !== null ? number_format($s['lecturer_score'], 2) : '-');
            $sheet->setCellValue('H' . $row, $s['admin_score'] !== null ? number_format($s['admin_score'], 2) : '-');
            $sheet->setCellValue('I' . $row, $s['final_score'] !== null ? number_format($s['final_score'], 2) : '-');
            $sheet->setCellValue('J' . $row, $s['final_grade'] ?? '-');
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'rekap_kp_kpl_koor_' . url_title($period['name'], '_', true) . '.xlsx';

        AuditService::log('EXPORT_EXCEL', 'kp_registrations', $periodId, null, null, 'Melakukan export rekapitulasi data mahasiswa periode ' . $period['name'] . ' ke format Excel');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function exportPdf()
    {
        $periodId = $this->request->getVar('period_id');
        if (empty($periodId)) {
            return redirect()->to(base_url('koordinator/laporan'))->with('error', 'Silakan pilih periode terlebih dahulu.');
        }

        $period = $this->periodModel->find($periodId);
        if (!$period) {
            return redirect()->to(base_url('koordinator/laporan'))->with('error', 'Periode tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $students = $db->table('kp_registrations')
            ->select('kp_registrations.*, student_profiles.npm, student_profiles.full_name, study_programs.name as prodi_name,
                      institution_profiles.name as institution_name,
                      final_scores.institution_score, final_scores.lecturer_score, final_scores.admin_score, final_scores.final_score, final_scores.final_grade')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
            ->join('final_scores', 'final_scores.registration_id = kp_registrations.id', 'left')
            ->join('placement_requests', 'placement_requests.registration_id = kp_registrations.id AND placement_requests.status = "disetujui"', 'left')
            ->join('institution_profiles', 'institution_profiles.id = placement_requests.institution_id', 'left')
            ->where('kp_registrations.period_id', $periodId)
            ->where('kp_registrations.deleted_at', null)
            ->get()
            ->getResultArray();

        // Construct HTML for landscape PDF report
        $html = '
        <html>
        <head>
            <title>Laporan Rekapitulasi Mahasiswa KP/KPL</title>
            <style>
                body { font-family: sans-serif; font-size: 10px; color: #333; }
                table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .text-center { text-align: center; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h2 { margin: 0; font-size: 16px; font-weight: bold; }
                .header p { margin: 5px 0 0 0; font-size: 11px; color: #666; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>LAPORAN REKAPITULASI NILAI KP/KPL MAHASISWA</h2>
                <p>Universitas Almuslim - Fakultas Ilmu Komputer</p>
                <p>Periode: ' . htmlspecialchars($period['name']) . '</p>
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">NPM</th>
                        <th>Nama Lengkap</th>
                        <th>Program Studi</th>
                        <th>Instansi Tempat</th>
                        <th class="text-center" style="width: 50px;">Nilai Instansi</th>
                        <th class="text-center" style="width: 50px;">Nilai Dosen</th>
                        <th class="text-center" style="width: 50px;">Nilai Admin</th>
                        <th class="text-center" style="width: 50px;">Nilai Akhir</th>
                        <th class="text-center" style="width: 30px;">Grade</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($students as $s) {
            $html .= '
            <tr>
                <td>' . htmlspecialchars($s['npm']) . '</td>
                <td>' . htmlspecialchars($s['full_name']) . '</td>
                <td>' . htmlspecialchars($s['prodi_name'] ?? '-') . '</td>
                <td>' . htmlspecialchars($s['institution_name'] ?? 'Mandiri / Belum Diplot') . '</td>
                <td class="text-center">' . ($s['institution_score'] !== null ? number_format($s['institution_score'], 2) : '-') . '</td>
                <td class="text-center">' . ($s['lecturer_score'] !== null ? number_format($s['lecturer_score'], 2) : '-') . '</td>
                <td class="text-center">' . ($s['admin_score'] !== null ? number_format($s['admin_score'], 2) : '-') . '</td>
                <td class="text-center"><strong>' . ($s['final_score'] !== null ? number_format($s['final_score'], 2) : '-') . '</strong></td>
                <td class="text-center">' . htmlspecialchars($s['final_grade'] ?? '-') . '</td>
            </tr>';
        }

        $html .= '
                </tbody>
            </table>
        </body>
        </html>';

        $dompdf = new Dompdf();
        
        $options = $dompdf->getOptions();
        $options->setIsPhpEnabled(false);
        $options->setIsHtml5ParserEnabled(true);
        $dompdf->setOptions($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $fileName = 'rekap_kp_kpl_koor_' . url_title($period['name'], '_', true) . '.pdf';

        AuditService::log('EXPORT_PDF', 'kp_registrations', $periodId, null, null, 'Melakukan export rekapitulasi data mahasiswa periode ' . $period['name'] . ' ke format PDF');

        $dompdf->stream($fileName, ["Attachment" => true]);
        exit;
    }
}
