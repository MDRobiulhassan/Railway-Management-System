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

Route::get('/search', function () {
    return view('search');
})->name('search');

Route::get('/search-result', function () {
    return view('search_result');
})->name('search.result');

Route::get('/booking-step1', function () {
    return view('booking_step1');
})->name('booking.step1');

Route::get('/booking-step2', function () {
    return view('booking_step2');
})->name('booking.step2');

Route::get('/booking-step3', function () {
    return view('booking_step3');
})->name('booking.step3');


Route::get('/booking-confirm', function () {
    return view('booking_confirm');
})->name('booking.confirm');


