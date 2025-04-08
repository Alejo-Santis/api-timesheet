<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TimesheetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */

Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/timesheets',  [TimesheetController::class, 'index']);
    Route::post('/timesheets', [TimesheetController::class, 'store']);
    Route::get('/timesheets/total', [TimesheetController::class, 'total']);
    Route::get('/timesheets/{id}', [TimesheetController::class, 'show']);
    Route::put('/timesheets/{id}', [TimesheetController::class, 'update']);
    Route::delete('/timesheets/{id}', [TimesheetController::class, 'destroy']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
