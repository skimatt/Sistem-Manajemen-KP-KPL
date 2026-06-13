<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LogbookWeekModel;
use App\Models\RegistrationModel;

class LogbookController extends BaseController
{
    protected $logbookModel;
    protected $registrationModel;

    public function __construct()
    {
        $this->logbookModel      = new LogbookWeekModel();
        $this->registrationModel = new RegistrationModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Logbook Mahasiswa',
            'students' => $this->logbookModel->getActiveStudentsLogbooksSummary(),
        ];
        return view('admin/logbook/index', $data);
    }

    public function view($registrationId)
    {
        $registration = $this->registrationModel->getRegistrationDetails($registrationId);
        if (!$registration) {
            return redirect()->to(base_url('admin/logbook'))->with('error', 'Data registrasi tidak ditemukan.');
        }

        // Fetch all logbook weeks for this student
        $weeks = $this->logbookModel->where('registration_id', $registrationId)
            ->where('deleted_at', null)
            ->orderBy('week_number', 'ASC')
            ->findAll();

        // Join daily entries for each week
        $db = \Config\Database::connect();
        foreach ($weeks as &$week) {
            $week['daily_entries'] = $db->table('logbook_daily_entries')
                ->where('logbook_week_id', $week['id'])
                ->orderBy('activity_date', 'ASC')
                ->get()
                ->getResultArray();

            $week['reviewer_name'] = $db->table('users')
                ->select('name')
                ->where('id', $week['approved_by'])
                ->get()
                ->getRow()->name ?? '-';
        }

        $data = [
            'title'        => 'Detail Logbook Mahasiswa',
            'registration' => $registration,
            'weeks'        => $weeks,
        ];
        return view('admin/logbook/view', $data);
    }
}
