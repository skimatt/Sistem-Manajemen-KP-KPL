<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FinalReportModel;

class LaporanController extends BaseController
{
    protected $reportModel;

    public function __construct()
    {
        $this->reportModel = new FinalReportModel();
    }

    public function index()
    {
        $data = [
            'title'   => 'Laporan Akhir Mahasiswa',
            'reports' => $this->reportModel->getReportsWithDetails(),
        ];
        return view('admin/laporan/index', $data);
    }

    public function download($id)
    {
        $report = $this->reportModel->find($id);
        if (!$report) {
            return redirect()->to(base_url('admin/laporan'))->with('error', 'Laporan akhir tidak ditemukan.');
        }

        $filePath = $report['file_path'];

        // If file_path is relative, prepend WRITEPATH or workspace root
        if (!file_exists($filePath)) {
            // Try inside WRITEPATH uploads directory
            $checkPath = WRITEPATH . 'uploads/' . $report['stored_name'];
            if (file_exists($checkPath)) {
                $filePath = $checkPath;
            } else {
                return redirect()->to(base_url('admin/laporan'))->with('error', 'Berkas fisik laporan tidak ditemukan di server.');
            }
        }

        // Return secure download
        return $this->response->download($filePath, null)
            ->setFileName($report['original_name']);
    }
}
