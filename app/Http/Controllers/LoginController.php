<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends ApiBaseController
{
    /**
     * Create a new controller instance.
     *
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware([
            'auth:sanctum',
        ])->except(['login', 'register']);
    }

    /**
     * Login api
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only(['email', 'password']);

            if (!Auth::attempt($credentials)) {

                return $this->responseError(trans('api.UNAUTHORIZED'));
            }

            $user = Auth::user();
            $token = $user->createToken('IpApp')->plainTextToken;

            $this->response = [
                'status' => true,
                'message' => trans('api.login'),
                'data' => [
                    'token' => $token,
                    'name' => $user->name
                ]
            ];

            return $this->responseSuccess($this->response);
        } catch (\Exception $e) {
            Log::error('LoginController@login error response ' . $e->getMessage(), $e->getTrace());

            return $this->responseInternalError(trans('api.ERROR'));
        }
    }

    /**
     * Get user profile details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(): JsonResponse
    {
        try {
            $data = auth()->user();

            $this->response = [
                'status' => true,
                'message' => trans('api.user_details'),
                'data' => $data
            ];

            return $this->responseSuccess($this->response);
        } catch (\Exception $e) {
            Log::error('LoginController@profile error response ' . $e->getMessage(), $e->getTrace());

            return $this->responseInternalError(trans('api.ERROR'));
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            auth()->user()->currentAccessToken()->delete();

            $this->response = [
                'status' => true,
                'message' => trans('api.logout'),
                'data' => null
            ];

            return $this->responseSuccess($this->response);
        } catch (\Exception $e) {
            Log::error('LoginController@logout error response ' . $e->getMessage(), $e->getTrace());

            return $this->responseInternalError(trans('api.ERROR'));
        }
    }
}
