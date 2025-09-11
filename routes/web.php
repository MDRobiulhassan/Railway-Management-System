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

Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');

Route::get('/search-result', [App\Http\Controllers\SearchController::class, 'searchResults'])->name('search.result');

Route::get('/schedule', [App\Http\Controllers\ScheduleController::class, 'index'])->name('schedule');
Route::get('/api/schedule/search', [App\Http\Controllers\ScheduleController::class, 'search'])->name('api.schedule.search');

// Booking routes
Route::post('/booking-start', [App\Http\Controllers\BookingController::class, 'start'])->name('booking.start');
Route::get('/booking-step1', [App\Http\Controllers\BookingController::class, 'step1'])->name('booking.step1');
Route::post('/booking-step2', [App\Http\Controllers\BookingController::class, 'step2'])->name('booking.step2');
Route::post('/booking-step3', [App\Http\Controllers\BookingController::class, 'step3'])->name('booking.step3');
Route::post('/booking-confirm', [App\Http\Controllers\BookingController::class, 'confirm'])->name('booking.confirm');
Route::get('/booking-confirm', [App\Http\Controllers\BookingController::class, 'confirmGet'])->name('booking.confirm.get');
Route::post('/booking-finalize', [App\Http\Controllers\BookingController::class, 'finalize'])->name('booking.finalize');

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

    Route::get('/adminpanel/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
    Route::post('/adminpanel/users', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::put('/adminpanel/users/{id}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/adminpanel/users/{id}', [App\Http\Controllers\AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::get('/adminpanel/users/{id}/edit', [App\Http\Controllers\AdminController::class, 'getUser'])->name('admin.users.edit');

    Route::get('/adminpanel/trains', [App\Http\Controllers\AdminController::class, 'trains'])->name('admin.trains');
    Route::post('/adminpanel/trains', [App\Http\Controllers\AdminController::class, 'storeTrain'])->name('admin.trains.store');
    Route::put('/adminpanel/trains/{id}', [App\Http\Controllers\AdminController::class, 'updateTrain'])->name('admin.trains.update');
    Route::delete('/adminpanel/trains/{id}', [App\Http\Controllers\AdminController::class, 'destroyTrain'])->name('admin.trains.destroy');
    Route::get('/adminpanel/trains/{id}/edit', [App\Http\Controllers\AdminController::class, 'getTrain'])->name('admin.trains.edit');

    Route::get('/adminpanel/stations', [App\Http\Controllers\AdminController::class, 'stations'])->name('admin.stations');
    Route::post('/adminpanel/stations', [App\Http\Controllers\AdminController::class, 'storeStation'])->name('admin.stations.store');
    Route::put('/adminpanel/stations/{id}', [App\Http\Controllers\AdminController::class, 'updateStation'])->name('admin.stations.update');
    Route::delete('/adminpanel/stations/{id}', [App\Http\Controllers\AdminController::class, 'destroyStation'])->name('admin.stations.destroy');
    Route::get('/adminpanel/stations/{id}/edit', [App\Http\Controllers\AdminController::class, 'getStation'])->name('admin.stations.edit');

    Route::get('/adminpanel/schedule', function () {
        return view('admin.schedule');
    })->name('admin.schedule');

    Route::get('/adminpanel/compartments', function () {
        return view('admin.compartments');
    })->name('admin.compartments');

    Route::get('/adminpanel/tickets', [App\Http\Controllers\AdminController::class, 'tickets'])->name('admin.tickets');
    Route::post('/adminpanel/tickets', [App\Http\Controllers\AdminController::class, 'storeTicket'])->name('admin.tickets.store');
    Route::put('/adminpanel/tickets/{id}', [App\Http\Controllers\AdminController::class, 'updateTicket'])->name('admin.tickets.update');
    Route::delete('/adminpanel/tickets/{id}', [App\Http\Controllers\AdminController::class, 'destroyTicket'])->name('admin.tickets.destroy');
    Route::get('/adminpanel/tickets/{id}/edit', [App\Http\Controllers\AdminController::class, 'getTicket'])->name('admin.tickets.edit');
    
    // API routes for dropdowns
    Route::get('/adminpanel/api/bookings', [App\Http\Controllers\AdminController::class, 'getBookings'])->name('admin.api.bookings');
    Route::get('/adminpanel/api/compartments', [App\Http\Controllers\AdminController::class, 'getCompartments'])->name('admin.api.compartments');
    Route::get('/adminpanel/api/seats', [App\Http\Controllers\AdminController::class, 'getSeats'])->name('admin.api.seats');
    Route::get('/adminpanel/api/trains', [App\Http\Controllers\AdminController::class, 'getTrains'])->name('admin.api.trains');

    Route::get('/adminpanel/ticket_prices', [App\Http\Controllers\AdminController::class, 'ticketPrices'])->name('admin.ticket_prices');
    Route::post('/adminpanel/ticket_prices', [App\Http\Controllers\AdminController::class, 'storeTicketPrice'])->name('admin.ticket_prices.store');
    Route::put('/adminpanel/ticket_prices/{id}', [App\Http\Controllers\AdminController::class, 'updateTicketPrice'])->name('admin.ticket_prices.update');
    Route::delete('/adminpanel/ticket_prices/{id}', [App\Http\Controllers\AdminController::class, 'destroyTicketPrice'])->name('admin.ticket_prices.destroy');
    Route::get('/adminpanel/ticket_prices/{id}/edit', [App\Http\Controllers\AdminController::class, 'getTicketPrice'])->name('admin.ticket_prices.edit');

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





