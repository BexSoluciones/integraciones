<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommandController;
use App\Http\Controllers\Api\FlatFileController;

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

//Rutas para ejecutar comandos
Route::group(['prefix' => 'commands'], function(){
    Route::post('/fyel/update/information', [CommandController::class, 'updateInformation']);
    Route::post('/fyel/upload/order', [CommandController::class, 'uploadOrder']);
});

//Rutas para descargar archivos planos de pedidos
Route::group(['prefix' => 'flatfile'], function(){
    Route::post('/fyel/download/pedido', [FlatFileController::class, 'download']);
});
