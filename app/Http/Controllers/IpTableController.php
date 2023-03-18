<?php

namespace App\Http\Controllers;

use App\Repositories\IpTableRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\IpTableRequest;
use App\Http\Resources\IpResource;
use App\Http\Resources\IpsResource;
use Illuminate\Support\Facades\Log;

class IpTableController extends ApiBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(
        private IpTableRepository $ipTableRepository,
        Request $request
    )
    {
        parent::__construct($request);
    }

    /**
     * Get IP List.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $ipList = $this->ipTableRepository->getIpList();

        $this->response = [
            'status' => true,
            'message' => 'Ip List',
            'data' => [
                'items' => new IpsResource($ipList->items()),
                'max_page' => $ipList->lastPage(),
                'current_page' => $ipList->currentPage(),
                'per_page' => $ipList->perPage(),
                'total' => $ipList->total(),
            ]
        ];

        return $this->responseSuccess($this->response);
    }

    /**
     * Store a new IP.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(IpTableRequest $request): JsonResponse
    {
        try {
            $ipData = $this->ipTableRepository->ipStore($request->all());

            $this->response = [
                'status' => true,
                'message' => 'Ip Address Created',
                'data' => new IpResource($ipData),
            ];

            return $this->responseSuccess($this->response);
        } catch (\Exception $e) {
            Log::error('IpTableController@store error response ' . $e->getMessage(), $e->getTrace());

            return $this->responseInternalError(trans('api.ERROR'));
        }
    }

    /**
     * Get ip table details.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $data = $this->ipTableRepository->getIpDetails($id);

            $this->response = [
                'status' => true,
                'message' => 'Ip address Details',
                'data' => new IpResource($data),
            ];

            return $this->responseSuccess($this->response);

        } catch (\Exception $e) {
            Log::error('IpTableController@show error response ' . $e->getMessage(), $e->getTrace());

            return $this->responseInternalError(trans('api.ERROR'));
        }
    }

    /**
     * update ip address.
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update(IpTableRequest $request, int $id): JsonResponse
    {
        try {
            $ipData =  $this->ipTableRepository->updateIp($request->all(), $id);
            $ipData->save();

            $this->response = [
                'status' => true,
                'message' => 'Ip address Updated',
                'data' => new IpResource($ipData),
            ];

            return $this->responseSuccess($this->response);

        } catch (\Exception $e) {
            Log::error('IpTableController@update error response ' . $e->getMessage(), $e->getTrace());

            return $this->responseInternalError(trans('api.ERROR'));
        }
    }
}
