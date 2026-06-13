<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PeriodModel;
use App\Models\RegistrationModel;
use App\Services\AuditService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

class ExportController extends BaseController
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
        $data = [
            'title'   => 'Laporan & Export Data',
            'periods' => $this->periodModel->where('deleted_at', null)->orderBy('id', 'DESC')->findAll(),
        ];
        return view('admin/laporan-export/index', $data);
    }

    public function exportExcel()
    {
        $periodId = $this->request->getVar('period_id');
        if (empty($periodId)) {
            return redirect()->to(base_url('admin/laporan-export'))->with('error', 'Silakan pilih periode terlebih dahulu.');
        }

        $period = $this->periodModel->find($periodId);
        if (!$period) {
            return redirect()->to(base_url('admin/laporan-export'))->with('error', 'Periode tidak ditemukan.');
        }

        // Fetch student registrations with grades details
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
        $sheet->setCellValue('A1', 'LAPORAN REKAPITULASI KP/KPL MAHASISWA');
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
        $fileName = 'rekap_kp_kpl_' . url_title($period['name'], '_', true) . '.xlsx';

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
            return redirect()->to(base_url('admin/laporan-export'))->with('error', 'Silakan pilih periode terlebih dahulu.');
        }

        $period = $this->periodModel->find($periodId);
        if (!$period) {
            return redirect()->to(base_url('admin/laporan-export'))->with('error', 'Periode tidak ditemukan.');
        }

        // Fetch student registrations with grades details
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

        // Construct html for PDF
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
        
        // Disable php execution for security
        $options = $dompdf->getOptions();
        $options->setIsPhpEnabled(false);
        $options->setIsHtml5ParserEnabled(true);
        $dompdf->setOptions($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $fileName = 'rekap_kp_kpl_' . url_title($period['name'], '_', true) . '.pdf';

        AuditService::log('EXPORT_PDF', 'kp_registrations', $periodId, null, null, 'Melakukan export rekapitulasi data mahasiswa periode ' . $period['name'] . ' ke format PDF');

        $dompdf->stream($fileName, ["Attachment" => true]);
        exit;
    }
}
