<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/',function(){
    DB::connection()->getPdo();
    echo 'Hmome';
});

Route::view('/teste', 'teste')->middleware('auth');

Route::get('/login', function(){
    echo 'formulario de login';
})->name('login');

Route::middleware('guest')->gourp(function(){
    Route::get('/register', function(){
        echo 'formulario de registro';
    })->name('register');
});

Route::get('/register', function(){
    echo 'formulario de registro';
})->name('register');