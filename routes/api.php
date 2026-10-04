<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SolicitacaoController;
use App\Http\Controllers\UserController;
use App\Http\Requests\SolicitacaoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    Route::resource('user', UserController::class); 
    Route::resource('solicitacoes', SolicitacaoController::class)->only(['store', 'index', 'show', 'update', 'destroy']);    
    
    });
