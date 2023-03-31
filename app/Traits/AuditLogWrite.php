<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\AuditLog;

trait AuditLogWrite
{
    /**
     * Log request and response in Database Table
     * @param $requests
     * @param $data
     */
    public function logActivity($data)
    {
        if (request()->method() == 'POST' || request()->method() == 'PUT') {
            $newApi = new AuditLog;
            $newApi->log_time = Carbon::now()->format('Y-m-d h:i:s');
            $newApi->request_path = request()->getUri();
            $newApi->request_id = $this->user ? $this->user->id : null;
            $newApi->post_json = json_encode($this->request);
            $newApi->final_response = json_encode($data);
            $newApi->request_ip = request()->ip();
            $newApi->save();
        }
    }
}
