<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Api\PpdbController;
use App\Http\Controllers\Api\PpdbDocumentController;
use App\Http\Controllers\Api\PpdbAdminController;
use App\Http\Controllers\Api\AdminDocumentController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);



/*
|--------------------------------------------------------------------------
| Protected
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/ppdb', [PpdbController::class, 'store']);
    Route::post('/ppdb/{ppdb}/documents', [PpdbDocumentController::class, 'store']);
    Route::get('/ppdb/me', [PpdbController::class, 'me']);

});

Route::middleware(['auth:sanctum', 'role:admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/ppdb', [PpdbAdminController::class, 'index']);
        Route::get('/ppdb/{id}', [PpdbAdminController::class, 'show']);
        Route::patch('/ppdb/{id}/status', [PpdbAdminController::class, 'updateStatus']);
        Route::get('/ppdb/{ppdb}/documents', [AdminDocumentController::class, 'index']);
        Route::patch('/documents/{document}', [AdminDocumentController::class, 'validateDocument']);
    });

// Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
//     return $request->user();
// });

