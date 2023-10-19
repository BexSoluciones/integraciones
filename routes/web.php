<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\AssemblyController;

use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['prefix'=>'panel', 'middleware'=>['auth']], function(){
    Route::get('/', [PanelController::class, 'index']);
});

//Routes for Assembly
Route::group(['prefix' => 'assembly','middleware' => ['auth:sanctum']], function(){
    Route::get('/register', [AssemblyController::class, 'register']);
    Route::post('/store', [AssemblyController::class, 'store']);
});