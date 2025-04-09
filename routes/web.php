<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

//usuarios nao autenticados
Route::middleware('guest')->group(function(){

    // login routes
    Route::get('/login', [AuthController::class , 'login'])->name('login');
    Route::post('/login', [AuthController::class , 'authenticate'])->name('authenticate');

    // registrarion routes
    Route::get('/register', [AuthController::class , 'register'])->name('register');
    Route::post('/register', [AuthController::class , 'store_user'])->name('store_user');
});

Route::middleware('auth')->group(function(){
    Route::get('/', function(){
        echo 'Olá mUndo';
    })->name('home');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
