<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuditLogResource;
use App\Repositories\AuditLogRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuditLogController extends ApiBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(
        private AuditLogRepository $auditLogRepository,
        Request $request
    )
    {
        parent::__construct($request);
    }

    /**
     * Get product List.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $auditList = $this->auditLogRepository->getAuditLogList();

            $this->response = [
                'status' => true,
                'message' => 'Audit Log List',
                'data' => [
                    'items' => new AuditLogResource($auditList->items()),
                    'max_page' => $auditList->lastPage(),
                    'current_page' => $auditList->currentPage(),
                    'per_page' => $auditList->perPage(),
                    'total' => $auditList->total(),
                ]
            ];

            return $this->responseSuccess($this->response);
        } catch (\Exception $e) {
            Log::error('AuditLogController@index error response ' . $e->getMessage());

            return $this->responseInternalError(trans('api.ERROR'));
        }
    }
}
