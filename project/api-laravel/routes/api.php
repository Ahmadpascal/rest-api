<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TodoController;
use App\Models\User;
use Illuminate\Support\Facades\Route;


// Public
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected: semua user harus login
Route::middleware('auth:sanctum')->group(function () {

    // CRUD todo milik user login
    Route::apiResource('todos', TodoController::class);

    // logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // ------------------
    // ADMIN ONLY ROUTES
    // ------------------
    Route::middleware('role:admin')->group(function () {

        // 1. List semua user
        Route::get('/admin/users', function () {
            return User::select('id', 'name', 'email', 'role', 'created_at')->get();
        });

        // 2. List semua todos
        Route::get('/admin/todos', function () {
            return \App\Models\Todo::with('user')->get();
        });

    });
});

Route::post('/debug-path', function () {
    return response()->json([
        'path' => request()->path(),
        'full_url' => request()->fullUrl(),
    ]);
});