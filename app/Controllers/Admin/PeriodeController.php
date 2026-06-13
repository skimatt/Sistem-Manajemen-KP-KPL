<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PeriodModel;
use App\Models\StudyProgramModel;
use App\Services\AuditService;

class PeriodeController extends BaseController
{
    protected $periodModel;
    protected $prodiModel;

    public function __construct()
    {
        $this->periodModel = new PeriodModel();
        $this->prodiModel  = new StudyProgramModel();
    }

    public function index()
    {
        // Fetch periods joined with prodi names
        $db = \Config\Database::connect();
        $periods = $db->table('kp_periods')
            ->select('kp_periods.*, study_programs.name as prodi_name, study_programs.code as prodi_code')
            ->join('study_programs', 'study_programs.id = kp_periods.study_program_id', 'left')
            ->where('kp_periods.deleted_at', null)
            ->orderBy('kp_periods.id', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'   => 'Periode KP/KPL',
            'periods' => $periods,
        ];
        return view('admin/periode/index', $data);
    }

    public function create()
    {
        $data = [
            'title'  => 'Tambah Periode',
            'prodis' => $this->prodiModel->where('status', 'active')->findAll(),
        ];
        return view('admin/periode/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'study_program_id'   => 'required|integer',
            'name'               => 'required|min_length[3]',
            'academic_year'      => 'required',
            'semester'           => 'required|in_list[Ganjil,Genap]',
            'activity_type'      => 'required|in_list[KP,KPL]',
            'registration_start' => 'required|valid_date[Y-m-d]',
            'registration_end'   => 'required|valid_date[Y-m-d]',
            'activity_start'     => 'required|valid_date[Y-m-d]',
            'activity_end'       => 'required|valid_date[Y-m-d]',
            'status'             => 'required|in_list[draft,aktif,ditutup,diarsipkan]',
        ];

        $messages = [
            'study_program_id' => [
                'required' => 'Program studi wajib dipilih.',
                'integer'  => 'Program studi tidak valid.',
            ],
            'name' => [
                'required'   => 'Nama periode wajib diisi.',
                'min_length' => 'Nama periode minimal 3 karakter.',
            ],
            'academic_year' => [
                'required' => 'Tahun akademik wajib diisi.',
            ],
            'semester' => [
                'required' => 'Semester wajib dipilih.',
                'in_list'  => 'Pilihan semester tidak valid.',
            ],
            'activity_type' => [
                'required' => 'Tipe kegiatan wajib dipilih.',
                'in_list'  => 'Pilihan tipe kegiatan tidak valid.',
            ],
            'registration_start' => [
                'required'   => 'Tanggal mulai pendaftaran wajib diisi.',
                'valid_date' => 'Format tanggal mulai pendaftaran tidak valid.',
            ],
            'registration_end' => [
                'required'   => 'Tanggal selesai pendaftaran wajib diisi.',
                'valid_date' => 'Format tanggal selesai pendaftaran tidak valid.',
            ],
            'activity_start' => [
                'required'   => 'Tanggal mulai kegiatan wajib diisi.',
                'valid_date' => 'Format tanggal mulai kegiatan tidak valid.',
            ],
            'activity_end' => [
                'required'   => 'Tanggal selesai kegiatan wajib diisi.',
                'valid_date' => 'Format tanggal selesai kegiatan tidak valid.',
            ],
            'status' => [
                'required' => 'Status periode wajib dipilih.',
                'in_list'  => 'Pilihan status periode tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Additional date comparison validation
        $regStart = $this->request->getPost('registration_start');
        $regEnd   = $this->request->getPost('registration_end');
        if (strtotime($regEnd) < strtotime($regStart)) {
            return redirect()->back()->withInput()->with('errors', ['registration_end' => 'Tanggal selesai pendaftaran tidak boleh lebih awal dari tanggal mulai pendaftaran.']);
        }

        $actStart = $this->request->getPost('activity_start');
        $actEnd   = $this->request->getPost('activity_end');
        if (strtotime($actEnd) < strtotime($actStart)) {
            return redirect()->back()->withInput()->with('errors', ['activity_end' => 'Tanggal selesai kegiatan tidak boleh lebih awal dari tanggal mulai kegiatan.']);
        }

        // Verify program study exists and activity type matches
        $prodiId = $this->request->getPost('study_program_id');
        $prodi   = $this->prodiModel->find($prodiId);
        if (!$prodi) {
            return redirect()->back()->withInput()->with('errors', ['study_program_id' => 'Program studi yang dipilih tidak ditemukan.']);
        }

        $activityType = $this->request->getPost('activity_type');
        if ($prodi['kp_label'] !== $activityType) {
            return redirect()->back()->withInput()->with('errors', ['activity_type' => "Tipe kegiatan ({$activityType}) tidak cocok dengan jenis kegiatan program studi ({$prodi['kp_label']})."]);
        }

        $periodData = [
            'study_program_id'   => $prodiId,
            'name'               => $this->request->getPost('name'),
            'academic_year'      => $this->request->getPost('academic_year'),
            'semester'           => $this->request->getPost('semester'),
            'activity_type'      => $activityType,
            'registration_start' => $regStart,
            'registration_end'   => $regEnd,
            'activity_start'     => $actStart,
            'activity_end'       => $actEnd,
            'status'             => $this->request->getPost('status'),
            'created_by'         => session()->get('user_id'),
        ];

        // If status is set to active, ensure we deactivate other active periods for this prodi and activity type
        if ($periodData['status'] === 'aktif') {
            $this->periodModel->where([
                'study_program_id' => $prodiId,
                'activity_type'    => $activityType,
                'status'           => 'aktif'
            ])->set(['status' => 'ditutup'])->update();
        }

        $periodId = $this->periodModel->insert($periodData);

        if ($periodId) {
            AuditService::log(
                'CREATE_PERIOD',
                'kp_periods',
                $periodId,
                null,
                $periodData,
                "Membuat periode akademik baru: " . $periodData['name']
            );
            return redirect()->to(base_url('admin/periode'))->with('success', 'Periode akademik berhasil ditambahkan.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan periode akademik.');
    }

    public function edit($id)
    {
        $period = $this->periodModel->find($id);
        if (!$period) {
            return redirect()->to(base_url('admin/periode'))->with('error', 'Periode akademik tidak ditemukan.');
        }

        $data = [
            'title'  => 'Edit Periode',
            'period' => $period,
            'prodis' => $this->prodiModel->where('status', 'active')->findAll(),
        ];
        return view('admin/periode/edit', $data);
    }

    public function update($id)
    {
        $period = $this->periodModel->find($id);
        if (!$period) {
            return redirect()->to(base_url('admin/periode'))->with('error', 'Periode akademik tidak ditemukan.');
        }

        $validation = \Config\Services::validation();

        $rules = [
            'study_program_id'   => 'required|integer',
            'name'               => 'required|min_length[3]',
            'academic_year'      => 'required',
            'semester'           => 'required|in_list[Ganjil,Genap]',
            'activity_type'      => 'required|in_list[KP,KPL]',
            'registration_start' => 'required|valid_date[Y-m-d]',
            'registration_end'   => 'required|valid_date[Y-m-d]',
            'activity_start'     => 'required|valid_date[Y-m-d]',
            'activity_end'       => 'required|valid_date[Y-m-d]',
            'status'             => 'required|in_list[draft,aktif,ditutup,diarsipkan]',
        ];

        $messages = [
            'study_program_id' => [
                'required' => 'Program studi wajib dipilih.',
                'integer'  => 'Program studi tidak valid.',
            ],
            'name' => [
                'required'   => 'Nama periode wajib diisi.',
                'min_length' => 'Nama periode minimal 3 karakter.',
            ],
            'academic_year' => [
                'required' => 'Tahun akademik wajib diisi.',
            ],
            'semester' => [
                'required' => 'Semester wajib dipilih.',
                'in_list'  => 'Pilihan semester tidak valid.',
            ],
            'activity_type' => [
                'required' => 'Tipe kegiatan wajib dipilih.',
                'in_list'  => 'Pilihan tipe kegiatan tidak valid.',
            ],
            'registration_start' => [
                'required'   => 'Tanggal mulai pendaftaran wajib diisi.',
                'valid_date' => 'Format tanggal mulai pendaftaran tidak valid.',
            ],
            'registration_end' => [
                'required'   => 'Tanggal selesai pendaftaran wajib diisi.',
                'valid_date' => 'Format tanggal selesai pendaftaran tidak valid.',
            ],
            'activity_start' => [
                'required'   => 'Tanggal mulai kegiatan wajib diisi.',
                'valid_date' => 'Format tanggal mulai kegiatan tidak valid.',
            ],
            'activity_end' => [
                'required'   => 'Tanggal selesai kegiatan wajib diisi.',
                'valid_date' => 'Format tanggal selesai kegiatan tidak valid.',
            ],
            'status' => [
                'required' => 'Status periode wajib dipilih.',
                'in_list'  => 'Pilihan status periode tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Additional date comparison validation
        $regStart = $this->request->getPost('registration_start');
        $regEnd   = $this->request->getPost('registration_end');
        if (strtotime($regEnd) < strtotime($regStart)) {
            return redirect()->back()->withInput()->with('errors', ['registration_end' => 'Tanggal selesai pendaftaran tidak boleh lebih awal dari tanggal mulai pendaftaran.']);
        }

        $actStart = $this->request->getPost('activity_start');
        $actEnd   = $this->request->getPost('activity_end');
        if (strtotime($actEnd) < strtotime($actStart)) {
            return redirect()->back()->withInput()->with('errors', ['activity_end' => 'Tanggal selesai kegiatan tidak boleh lebih awal dari tanggal mulai kegiatan.']);
        }

        // Verify program study exists and activity type matches
        $prodiId = $this->request->getPost('study_program_id');
        $prodi   = $this->prodiModel->find($prodiId);
        if (!$prodi) {
            return redirect()->back()->withInput()->with('errors', ['study_program_id' => 'Program studi yang dipilih tidak ditemukan.']);
        }

        $activityType = $this->request->getPost('activity_type');
        if ($prodi['kp_label'] !== $activityType) {
            return redirect()->back()->withInput()->with('errors', ['activity_type' => "Tipe kegiatan ({$activityType}) tidak cocok dengan jenis kegiatan program studi ({$prodi['kp_label']})."]);
        }

        $periodData = [
            'study_program_id'   => $prodiId,
            'name'               => $this->request->getPost('name'),
            'academic_year'      => $this->request->getPost('academic_year'),
            'semester'           => $this->request->getPost('semester'),
            'activity_type'      => $activityType,
            'registration_start' => $regStart,
            'registration_end'   => $regEnd,
            'activity_start'     => $actStart,
            'activity_end'       => $actEnd,
            'status'             => $this->request->getPost('status'),
        ];

        $oldValues = [
            'study_program_id'   => $period['study_program_id'],
            'name'               => $period['name'],
            'academic_year'      => $period['academic_year'],
            'semester'           => $period['semester'],
            'activity_type'      => $period['activity_type'],
            'registration_start' => $period['registration_start'],
            'registration_end'   => $period['registration_end'],
            'activity_start'     => $period['activity_start'],
            'activity_end'       => $period['activity_end'],
            'status'             => $period['status'],
        ];

        // If status is set to active, ensure we deactivate other active periods for this prodi and activity type
        if ($periodData['status'] === 'aktif') {
            $this->periodModel->where([
                'study_program_id' => $prodiId,
                'activity_type'    => $activityType,
                'status'           => 'aktif',
            ])->where('id !=', $id)->set(['status' => 'ditutup'])->update();
        }

        if ($this->periodModel->update($id, $periodData)) {
            AuditService::log(
                'UPDATE_PERIOD',
                'kp_periods',
                $id,
                $oldValues,
                $periodData,
                "Memperbarui periode akademik: " . $periodData['name']
            );
            return redirect()->to(base_url('admin/periode'))->with('success', 'Periode akademik berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui periode akademik.');
    }

    public function delete($id)
    {
        $period = $this->periodModel->find($id);
        if (!$period) {
            return redirect()->to(base_url('admin/periode'))->with('error', 'Periode akademik tidak ditemukan.');
        }

        // Check if there are registrations in this period
        $db = \Config\Database::connect();
        $registrationsCount = $db->table('kp_registrations')
            ->where('period_id', $id)
            ->where('deleted_at', null)
            ->countAllResults();

        if ($registrationsCount > 0) {
            return redirect()->to(base_url('admin/periode'))->with('error', 'Gagal menghapus periode. Terdapat data pendaftaran mahasiswa yang aktif pada periode ini.');
        }

        if ($this->periodModel->delete($id)) {
            AuditService::log(
                'DELETE_PERIOD',
                'kp_periods',
                $id,
                [
                    'name' => $period['name'],
                    'status' => $period['status']
                ],
                null,
                "Menghapus (soft delete) periode akademik: " . $period['name']
            );
            return redirect()->to(base_url('admin/periode'))->with('success', 'Periode akademik berhasil dihapus.');
        }

        return redirect()->to(base_url('admin/periode'))->with('error', 'Gagal menghapus periode akademik.');
    }
}
