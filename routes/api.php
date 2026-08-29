
<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC API ROUTES
// ============================================================

Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);


// ============================================================
// AUTHENTICATED API ROUTES
// ============================================================

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', [AuthController::class, 'user']);
});
