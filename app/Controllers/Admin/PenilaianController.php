<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FinalScoreModel;
use App\Models\RegistrationModel;

class PenilaianController extends BaseController
{
    protected $finalScoreModel;
    protected $registrationModel;

    public function __construct()
    {
        $this->finalScoreModel   = new FinalScoreModel();
        $this->registrationModel = new RegistrationModel();
    }

    public function index()
    {
        $data = [
            'title'  => 'Monitoring Penilaian',
            'scores' => $this->finalScoreModel->getFinalScoresWithDetails(),
        ];
        return view('admin/penilaian/index', $data);
    }

    public function view($id)
    {
        $finalScore = $this->finalScoreModel->find($id);
        if (!$finalScore) {
            return redirect()->to(base_url('admin/penilaian'))->with('error', 'Data penilaian tidak ditemukan.');
        }

        $registration = $this->registrationModel->getRegistrationDetails($finalScore['registration_id']);
        if (!$registration) {
            return redirect()->to(base_url('admin/penilaian'))->with('error', 'Data registrasi tidak ditemukan.');
        }

        // Fetch detailed component scores
        $db = \Config\Database::connect();
        $componentScores = $db->table('assessment_scores')
            ->select('assessment_scores.*, assessment_components.component_name, assessment_components.weight, assessment_components.max_score')
            ->join('assessment_components', 'assessment_components.id = assessment_scores.component_id')
            ->where('assessment_scores.registration_id', $finalScore['registration_id'])
            ->get()
            ->getResultArray();

        $data = [
            'title'           => 'Detail Nilai Mahasiswa',
            'finalScore'      => $finalScore,
            'registration'    => $registration,
            'componentScores' => $componentScores,
        ];
        return view('admin/penilaian/view', $data);
    }
}
