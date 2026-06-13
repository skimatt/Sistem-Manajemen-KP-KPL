<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PlacementRequestModel;

class PenempatanController extends BaseController
{
    protected $placementModel;

    public function __construct()
    {
        $this->placementModel = new PlacementRequestModel();
    }

    public function index()
    {
        $data = [
            'title'     => 'Data Penempatan',
            'placements' => $this->placementModel->getPlacementRequestsWithDetails(),
        ];
        return view('admin/penempatan/index', $data);
    }

    public function view($id)
    {
        $placement = $this->placementModel->getPlacementRequestDetails($id);
        if (!$placement) {
            return redirect()->to(base_url('admin/penempatan'))->with('error', 'Data pengajuan penempatan tidak ditemukan.');
        }

        // Fetch student choices (priority targets)
        $db = \Config\Database::connect();
        $choices = $db->table('placement_choices')
            ->where('registration_id', $placement['registration_id'])
            ->orderBy('priority_order', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title'     => 'Detail Pengajuan Penempatan',
            'placement' => $placement,
            'choices'   => $choices,
        ];
        return view('admin/penempatan/view', $data);
    }
}
