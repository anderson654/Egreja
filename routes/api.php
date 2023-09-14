<?php

use App\Http\Controllers\ZApiWebHookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('webhook/zapi')->group(function () {
    Route::post('aoreceber', [ZApiWebHookController::class, 'getStatusMessage']);
    // Outras rotas do webhook, se necessário
});
