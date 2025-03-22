<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\CategoriaController;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::controller(CategoriaController::class)->group(function(){
    Route::get('/categorias', 'index');
});




require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
