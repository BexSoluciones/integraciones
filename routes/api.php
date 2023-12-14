<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommandController;
use App\Http\Controllers\Api\FlatFileController;
use App\Http\Controllers\Api\ImportationController;

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

Route::group(['prefix' => 'prueba','middleware' => ['auth:sanctum', 'throttle:500,1']], function(){
    Route::get('/', [AuthController::class, 'prueba']);
});

// Rutas para ejecutar comandos
Route::group(['prefix' => 'commands', 'middleware' => ['auth:sanctum', 'throttle:500,1']], function(){
    Route::post('/update/information', [CommandController::class, 'updateInformation']);
    // Sube todos los pedidos
    Route::post('/upload/order', [CommandController::class, 'uploadOrder']);
});

// Rutas para consultar el estado de las importaciones
Route::group(['prefix' => 'importation', 'middleware' => ['auth:sanctum', 'throttle:500,1']], function(){
    Route::post('/consult/state', [ImportationController::class, 'consultState']);
});

// Rutas para descargar archivos planos de pedidos
Route::group(['prefix' => 'flatfile', 'middleware' => ['auth:sanctum', 'throttle:500,1']], function(){
    Route::post('/download/pedido', [FlatFileController::class, 'download']);
});
