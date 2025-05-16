<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

//usuarios nao autenticados
Route::middleware('guest')->group(function () {

    // login routes
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');

    // registrarion routes
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store_user'])->name('store_user');

    // new user confirmation
    Route::get('/new_user_confirmation/{token}', [AuthController::class, 'new_user_confirmation'])->name('new_user_confirmation');

    // forgot password
    Route::get('/forgot_password', [AuthController::class, 'forgot_password'])->name('forgot_password');
    Route::post('/forgot_password', [AuthController::class, 'send_reset_password_link'])->name('send_reset_password_link');

    // reset passsword
    Route::get('/reset_password/{token}', [AuthController::class, 'reset_password'])->name('reset_password');
    Route::post('/reset_password', [AuthController::class, 'reset_password_update'])->name('reset_password_update');
});

Route::middleware('auth')->group(function () {

    //entradaa
    Route::get('/', [MainController::class, 'home'])->name('home');

    // profile - change pasword
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'change_password'])->name('change_password');

    //criar atendimento 
    Route::get('/atendimento', [AuthController::class, 'selectAtendimento'])->name('selectAtendimento');
    Route::post('/atendimento', [AuthController::class, 'insertAtendimento'])->name('insertAtendimento');

    //editar atendimento
    Route::get('/atendimento/{id}/editar', [AuthController::class, 'editarAtendimento'])->name('editarAtendimento');
    Route::post('/atendimento/{id}/atualizar', [AuthController::class, 'atualizarAtendimento'])->name('atualizarAtendimento');


    // nova categoria
    Route::get('/categoria', [AuthController::class, 'categoria'])->name('categoria');
    Route::post('/categoria', [AuthController::class, 'insertCategoria'])->name('insertCategoria');

    // delete accout
    Route::post('/deleted_account', [AuthController::class, 'deleted_account'])->name('deleted_account');

    // logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
