<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TopsisCriteriaModel;
use App\Models\TopsisWeightModel;
use App\Models\PeriodModel;
use App\Services\AuditService;

class TopsisController extends BaseController
{
    protected $criteriaModel;
    protected $weightModel;
    protected $periodModel;

    public function __construct()
    {
        $this->criteriaModel = new TopsisCriteriaModel();
        $this->weightModel   = new TopsisWeightModel();
        $this->periodModel   = new PeriodModel();
    }

    public function index()
    {
        // Fetch periods to populate dropdown
        $periods = $this->periodModel->where('deleted_at', null)->orderBy('id', 'DESC')->findAll();
        
        // Determine selected period (default to first active period, or first period overall)
        $selectedPeriodId = $this->request->getVar('period_id');
        if (empty($selectedPeriodId)) {
            $activePeriod = $this->periodModel->where('status', 'aktif')->first();
            $selectedPeriodId = $activePeriod ? $activePeriod['id'] : ($periods ? $periods[0]['id'] : null);
        }

        $criteria = $this->criteriaModel->where('status', 'active')->findAll();
        $weights = [];

        if ($selectedPeriodId) {
            // Fetch existing weights
            $existingWeights = $this->weightModel->where('period_id', $selectedPeriodId)->findAll();
            
            // Map existing weights by criteria_id
            $mappedWeights = [];
            foreach ($existingWeights as $w) {
                $mappedWeights[$w['criteria_id']] = $w['weight'];
            }

            // Ensure every active criteria has a weight entry (insert if missing)
            foreach ($criteria as $c) {
                if (!isset($mappedWeights[$c['id']])) {
                    // Pre-fill weight as 0.00
                    $this->weightModel->insert([
                        'period_id'   => $selectedPeriodId,
                        'criteria_id' => $c['id'],
                        'weight'      => 0.00,
                    ]);
                    $mappedWeights[$c['id']] = 0.00;
                }
            }

            // Re-fetch weights with details
            $weights = $this->weightModel->getWeightsByPeriod($selectedPeriodId);
        }

        $data = [
            'title'            => 'Kriteria & Bobot TOPSIS',
            'periods'          => $periods,
            'selectedPeriodId' => $selectedPeriodId,
            'criteria'         => $criteria,
            'weights'          => $weights,
        ];

        return view('admin/topsis/index', $data);
    }

    public function updateWeights()
    {
        $periodId = $this->request->getPost('period_id');
        $weightsInput = $this->request->getPost('weights'); // Array criteria_id => weight value

        if (empty($periodId) || empty($weightsInput) || !is_array($weightsInput)) {
            return redirect()->back()->with('error', 'Data input tidak lengkap.');
        }

        // Validate that weights sum up to exactly 100%
        $totalSum = 0;
        foreach ($weightsInput as $criteriaId => $weight) {
            $totalSum += floatval($weight);
        }

        // Allow slight float imprecision margin (e.g. 99.99 - 100.01)
        if (abs($totalSum - 100) > 0.01) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui! Total akumulasi bobot kriteria harus tepat 100%. (Akumulasi saat ini: ' . $totalSum . '%)');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $oldWeights = $this->weightModel->where('period_id', $periodId)->findAll();

        foreach ($weightsInput as $criteriaId => $weight) {
            $this->weightModel->where('period_id', $periodId)
                ->where('criteria_id', $criteriaId)
                ->update(null, ['weight' => floatval($weight)]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memperbarui bobot kriteria karena kesalahan internal.');
        }

        $newWeights = $this->weightModel->where('period_id', $periodId)->findAll();
        $period = $this->periodModel->find($periodId);

        AuditService::log(
            'UPDATE_TOPSIS_WEIGHTS',
            'topsis_weights',
            $periodId,
            $oldWeights,
            $newWeights,
            'Memperbarui bobot TOPSIS untuk periode: ' . ($period['name'] ?? $periodId)
        );

        return redirect()->to(base_url('admin/topsis?period_id=' . $periodId))->with('success', 'Bobot kriteria TOPSIS berhasil diperbarui.');
    }
}
