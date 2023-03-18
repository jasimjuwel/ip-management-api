<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use App\Traits\AuditLogWrite;
use Illuminate\Support\Facades\Auth;

class ApiBaseController extends Controller
{
    /**
     * To write Request/Response Log
     */
    use AuditLogWrite;

    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @var string
     */
    protected $message;

    public $user = [];
    public $request = [];
    public $response = [];

    /**
     * ApiBaseController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->response = ['status' => false, 'message' => ''];
        $this->request = $request->all();
        $this->user = Auth::guard('sanctum')->user();
    }

    /**
     * @param string $message
     *
     * @param string $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseWithError(string $message,mixed $data = null): JsonResponse
    {
        return $this->response([
            'status' => false,
            'code' => 422,
            'message' => $message,
            'data' => null,
            'errors' => $data,
        ]);
    }

    /**
     * @param array $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function response(array $data, array $headers = []): JsonResponse
    {
        $this->logActivity($data);

        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     *
     * @return $this
     */
    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param array $responseArray
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess(array $responseArray): JsonResponse
    {
        $data =
            [
                'status' => true,
                'code' => $this->getStatusCode(),
            ]
            + $responseArray +
            [
                'errors' => []
            ];

        return $this->setStatusCode(IlluminateResponse::HTTP_OK)->response($data);
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccessWithOnlyData(array $data): JsonResponse
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_OK)->response(['data' => $data]);
    }

    /**
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseForbidden(array $data): JsonResponse
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_FORBIDDEN)->responseWithError(trans('api.FORBIDDEN'), $data);
    }

    /**
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseUnauthorized(array $data): JsonResponse
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_UNAUTHORIZED)->responseWithError(trans('api.UNAUTHORIZED'), $data);
    }

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseMethodNotAllowed(): JsonResponse
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_METHOD_NOT_ALLOWED)->responseWithError(trans('api.METHOD_NOT_ALLOWED'));
    }

    /**
     *
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseValidationError(array $errors): JsonResponse
    {
        //        422 removed to solve android exception as developer request

        return $this->setStatusCode(IlluminateResponse::HTTP_OK)->responseWithError(trans('api.INPUT_INVALID'), $errors); //$errors->all()
    }

    /**
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseNotFound(array $data): JsonResponse
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_NOT_FOUND)->responseWithError(trans('api.RESPONSE_NOT_FOUND'), $data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function httpNotFound(): JsonResponse
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_NOT_FOUND)->responseWithError(trans('api.URL_NOT_FOUND'));
    }

    /**
     *
     * @param string $message
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseError(string $message, array $errors = []): JsonResponse
    {
        //        422 removed to solve android exception as developer request

        return $this->setStatusCode(IlluminateResponse::HTTP_OK)->responseWithError($message, $errors);
    }

    /**
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseInternalError(string $message): JsonResponse
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR)->responseWithError(trans('api.INTERNAL_SERVER_ERROR'), [$message]);
    }

}
