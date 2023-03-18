<?php

namespace App\Repositories;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Log;

class AuditLogRepository
{
    /**
     * Audit Log list
     * @param $perPage
     */
    public function getAuditLogList(int $perPage = 10)
    {
        try {
            return AuditLog::orderBy('id', 'desc')->paginate($perPage);
        } catch (\Exception $e) {
            Log::error('AuditLogRepository@getAuditLogList response message: ' . $e->getMessage(), $e->getTrace());

            return false;
        }
    }
}
