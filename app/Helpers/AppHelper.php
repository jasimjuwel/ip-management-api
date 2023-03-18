<?php

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

/**
 *
 * @param string $data
 * @return JsonResponse
 */
if (!function_exists('validationErrorResponse')) {
    function validationErrorResponse($message): JsonResponse
    {
        return response()->json([
            'status' => false,
            'code' => 422,
            'message' => trans('api.INPUT_INVALID'),
            'data' => null,
            'errors' => $message,
        ]);
    }
}

/**
 * @param string $date
 * @return string
 */
if (!function_exists('dateTimeConvertDBtoForm')) {
    function dateTimeConvertDBtoForm($date): string
    {
        if (!empty($date)) {
            return \Carbon\Carbon::parse($date)->format('d-m-Y H:i:s');
        }
    }
}
