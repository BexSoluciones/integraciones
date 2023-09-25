<?php

use App\Http\Controllers\Api\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Si el usuario no esta logueado
Route::get('login', function () {
    return response()->json(['status' => 401, 'message' => 'unauthorized, please login']);
})->name('login');

Route::group(['prefix' => 'authenticate'], function(){
    //hace el login 
    Route::post('login', [AuthController::class, 'login']);
});

Route::group(['prefix' => 'prueba','middleware' => ['auth:sanctum']], function(){
    Route::get('/', [AuthController::class, 'prueba']);
});

