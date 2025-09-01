<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Registration form
Route::get('/register', function () {
    return view('register');
})->name('register.form');

// Register form submission 
Route::post('/register', function () {
    return redirect()->route('login.form');
})->name('c');

// Login form
Route::get('/login', function () {
    return view('login');
})->name('login.form');

// Login form submission 
Route::post('/login', function () {
    return redirect()->route('login.form');
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

Route::get('/user_profile', function () {
    return view('user_profile');
})->name('user.profile');

Route::get('/user_dashboard', function () {
    return view('user_dashboard');
})->name('user.dashboard');

Route::get('/adminpanel', function () {
    return view('admin.adminpanel');
})->name('adminpanel');

Route::get('/adminpanel/users', function () {
    return view('admin.users');
})->name('admin.users');
Route::get('/adminpanel/trains', function () {
    return view('admin.trains');
})->name('admin.trains');
Route::get('/adminpanel/stations', function () {
    return view('admin.stations');
})->name('admin.stations');
Route::get('/adminpanel/schedule', function () {
    return view('admin.schedule');
})->name('admin.schedule');
Route::get('/adminpanel/compartments', function () {
    return view('admin.compartments');
})->name('admin.compartments');
Route::get('/adminpanel/tickets', function () {
    return view('admin.tickets');
})->name('admin.tickets');
Route::get('/adminpanel/ticket_prices', function () {
    return view('admin.ticket_prices');
})->name('admin.ticket_prices');

Route::get('/adminpanel/seats', function () {
    return view('admin.seats');
})->name('admin.seats');
Route::get('/adminpanel/bookings', function () {
    return view('admin.bookings');
})->name('admin.bookings');

Route::get('/adminpanel/payments', function () {
    return view('admin.payments');
})->name('admin.payments');

Route::get('/adminpanel/nid', function () {
    return view('admin.nid');
})->name('admin.nid');

Route::get('/adminpanel/food_items', function () {
    return view('admin.food_items');
})->name('admin.food_items');

Route::get('/adminpanel/food_order', function () {
    return view('admin.food_order');
})->name('admin.food_order');





