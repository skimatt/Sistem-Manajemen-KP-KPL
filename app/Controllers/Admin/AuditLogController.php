<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;

class AuditLogController extends BaseController
{
    protected $auditModel;

    public function __construct()
    {
        $this->auditModel = new AuditLogModel();
    }

    public function index()
    {
        // Filters
        $actionFilter = $this->request->getVar('action');
        $emailFilter  = $this->request->getVar('email');
        $dateFilter   = $this->request->getVar('date');

        // Build query
        $query = $this->auditModel->select('audit_logs.*, users.email, users.name as user_name')
            ->join('users', 'users.id = audit_logs.user_id', 'left');

        if (!empty($actionFilter)) {
            $query = $query->like('audit_logs.action', $actionFilter);
        }

        if (!empty($emailFilter)) {
            $query = $query->like('users.email', $emailFilter);
        }

        if (!empty($dateFilter)) {
            $query = $query->like('audit_logs.created_at', $dateFilter);
        }

        // Paginate results
        $logs = $query->orderBy('audit_logs.id', 'DESC')->paginate(20, 'logs');

        // Fetch unique actions to populate filter select dropdown
        $db = \Config\Database::connect();
        $uniqueActions = $db->table('audit_logs')->select('action')->distinct()->get()->getResultArray();

        $data = [
            'title'         => 'Audit Log Aktivitas',
            'logs'          => $logs,
            'pager'         => $this->auditModel->pager,
            'actionFilter'  => $actionFilter,
            'emailFilter'   => $emailFilter,
            'dateFilter'    => $dateFilter,
            'uniqueActions' => array_column($uniqueActions, 'action'),
        ];

        return view('admin/audit-log/index', $data);
    }
}
