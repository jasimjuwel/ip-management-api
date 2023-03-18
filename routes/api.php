<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\IpTableController;
use App\Http\Controllers\AuditLogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [LoginController::class, 'profile']);
    Route::post('/logout', [LoginController::class, 'logout']);

    Route::controller(IpTableController::class)->group(function () {
        Route::post('ip-store', 'store');
        Route::get('ip-list', 'index');
        Route::get('ip-show/{id}', 'show');
        Route::get('ip-edit/{id}', 'edit');
        Route::put('ip-update/{id}', 'update');
    });

    Route::controller(AuditLogController::class)->group(function () {
        Route::get('audit-log-list', 'index');
    });
});

