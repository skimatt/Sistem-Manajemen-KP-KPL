<?php

namespace App\Services;

class AuditService
{
    /**
     * Log an action to audit_logs
     *
     * @param string $action      e.g. 'CREATE_STUDENT', 'UPDATE_LECTURER', etc.
     * @param string|null $tableName
     * @param int|null $recordId
     * @param array|null $oldValues
     * @param array|null $newValues
     * @param string|null $note
     * @return bool
     */
    public static function log($action, $tableName = null, $recordId = null, $oldValues = null, $newValues = null, $note = null)
    {
        try {
            $db = \Config\Database::connect();
            $session = session();

            $userId = $session->get('user_id');
            $role = $session->get('role') ?? 'system';

            $request = \Config\Services::request();
            $ipAddress = $request->getIPAddress();
            $userAgent = $request->getUserAgent()->getAgentString();

            $db->table('audit_logs')->insert([
                'user_id'    => $userId,
                'role'       => $role,
                'action'     => $action,
                'table_name' => $tableName,
                'record_id'  => $recordId,
                'old_values' => $oldValues ? json_encode($oldValues) : null,
                'new_values' => $newValues ? json_encode($newValues) : null,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'note'       => $note,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            return true;
        } catch (\Exception $e) {
            log_message('error', 'Failed to write audit log: ' . $e->getMessage());
            return false;
        }
    }
}
