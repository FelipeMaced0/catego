<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoriaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::controller(CategoriaController::class)->group(function(){
    Route::get('/categorias', 'list');
    Route::post('/categoria', 'store');
    Route::get('/categoria/{categoria}', 'show');
    Route::put('/categoria/{categoria}', 'update');
    Route::delete('/categoria/{categoria}', 'destroy');
});
