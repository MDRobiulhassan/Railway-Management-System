<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication routes
Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register.submit');
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

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

Route::get('/search-result', action: function () {
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

// Protected user routes
Route::middleware(['auth'])->group(function () {
    Route::get('/user_dashboard', function () {
        return view('user_dashboard');
    })->name('user.dashboard');
    
    Route::get('/user_profile', function () {
        return view('user_profile');
    })->name('user.profile');
});

// Protected admin routes
Route::middleware(['auth', 'admin'])->group(function () {
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
});





