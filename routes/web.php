<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Registration form
Route::get('/register', function () {
    return view('register');
})->name('register.form');

// Register form submission 
Route::post('/register', function () {
    return redirect()->route('login.form');
})->name('register.submit');

// Login form
Route::get('/login', function () {
    return view('login');
})->name('login.form');

// Login form submission 
Route::post('/login', function () {
    return redirect()->route('login.form'); // redirecting back to login for now
})->name('login.submit');

// Forgot password form
Route::get('/password/forgot', function () {
    return view('forgot');
})->name('password.form');

// Forgot password submission 
Route::post('/password/forgot', function () {
    return redirect()->route('login.form');
})->name('password.submit');
