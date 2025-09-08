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

    // Users
    Route::get('/adminpanel/users', [App\Http\Controllers\Admin\UsersController::class, 'index'])->name('admin.users.index');
    Route::post('/adminpanel/users', [App\Http\Controllers\Admin\UsersController::class, 'store'])->name('admin.users.store');
    Route::put('/adminpanel/users/{user}', [App\Http\Controllers\Admin\UsersController::class, 'update'])->name('admin.users.update');
    Route::delete('/adminpanel/users/{user}', [App\Http\Controllers\Admin\UsersController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/adminpanel/trains', [App\Http\Controllers\Admin\TrainsController::class, 'index'])->name('admin.trains');
    Route::post('/adminpanel/trains', [App\Http\Controllers\Admin\TrainsController::class, 'store'])->name('admin.trains.store');
    Route::put('/adminpanel/trains/{train}', [App\Http\Controllers\Admin\TrainsController::class, 'update'])->name('admin.trains.update');
    Route::delete('/adminpanel/trains/{train}', [App\Http\Controllers\Admin\TrainsController::class, 'destroy'])->name('admin.trains.destroy');

    Route::get('/adminpanel/stations', [App\Http\Controllers\Admin\StationsController::class, 'index'])->name('admin.stations');
    Route::post('/adminpanel/stations', [App\Http\Controllers\Admin\StationsController::class, 'store'])->name('admin.stations.store');
    Route::put('/adminpanel/stations/{station}', [App\Http\Controllers\Admin\StationsController::class, 'update'])->name('admin.stations.update');
    Route::delete('/adminpanel/stations/{station}', [App\Http\Controllers\Admin\StationsController::class, 'destroy'])->name('admin.stations.destroy');

    Route::get('/adminpanel/schedule', [App\Http\Controllers\Admin\SchedulesController::class, 'index'])->name('admin.schedule');
    Route::post('/adminpanel/schedule', [App\Http\Controllers\Admin\SchedulesController::class, 'store'])->name('admin.schedule.store');
    Route::put('/adminpanel/schedule/{schedule}', [App\Http\Controllers\Admin\SchedulesController::class, 'update'])->name('admin.schedule.update');
    Route::delete('/adminpanel/schedule/{schedule}', [App\Http\Controllers\Admin\SchedulesController::class, 'destroy'])->name('admin.schedule.destroy');

    Route::get('/adminpanel/compartments', [App\Http\Controllers\Admin\CompartmentsController::class, 'index'])->name('admin.compartments');
    Route::post('/adminpanel/compartments', [App\Http\Controllers\Admin\CompartmentsController::class, 'store'])->name('admin.compartments.store');
    Route::put('/adminpanel/compartments/{compartment}', [App\Http\Controllers\Admin\CompartmentsController::class, 'update'])->name('admin.compartments.update');
    Route::delete('/adminpanel/compartments/{compartment}', [App\Http\Controllers\Admin\CompartmentsController::class, 'destroy'])->name('admin.compartments.destroy');

    Route::get('/adminpanel/tickets', [App\Http\Controllers\Admin\TicketsController::class, 'index'])->name('admin.tickets');
    Route::post('/adminpanel/tickets', [App\Http\Controllers\Admin\TicketsController::class, 'store'])->name('admin.tickets.store');
    Route::put('/adminpanel/tickets/{ticket}', [App\Http\Controllers\Admin\TicketsController::class, 'update'])->name('admin.tickets.update');
    Route::delete('/adminpanel/tickets/{ticket}', [App\Http\Controllers\Admin\TicketsController::class, 'destroy'])->name('admin.tickets.destroy');

    Route::get('/adminpanel/ticket_prices', [App\Http\Controllers\Admin\TicketPricesController::class, 'index'])->name('admin.ticket_prices');
    Route::post('/adminpanel/ticket_prices', [App\Http\Controllers\Admin\TicketPricesController::class, 'store'])->name('admin.ticket_prices.store');
    Route::put('/adminpanel/ticket_prices/{ticket_price}', [App\Http\Controllers\Admin\TicketPricesController::class, 'update'])->name('admin.ticket_prices.update');
    Route::delete('/adminpanel/ticket_prices/{ticket_price}', [App\Http\Controllers\Admin\TicketPricesController::class, 'destroy'])->name('admin.ticket_prices.destroy');

    Route::get('/adminpanel/seats', [App\Http\Controllers\Admin\SeatsController::class, 'index'])->name('admin.seats');
    Route::post('/adminpanel/seats', [App\Http\Controllers\Admin\SeatsController::class, 'store'])->name('admin.seats.store');
    Route::put('/adminpanel/seats/{seat}', [App\Http\Controllers\Admin\SeatsController::class, 'update'])->name('admin.seats.update');
    Route::delete('/adminpanel/seats/{seat}', [App\Http\Controllers\Admin\SeatsController::class, 'destroy'])->name('admin.seats.destroy');

    Route::get('/adminpanel/bookings', [App\Http\Controllers\Admin\BookingsController::class, 'index'])->name('admin.bookings');
    Route::put('/adminpanel/bookings/{booking}', [App\Http\Controllers\Admin\BookingsController::class, 'update'])->name('admin.bookings.update');
    Route::delete('/adminpanel/bookings/{booking}', [App\Http\Controllers\Admin\BookingsController::class, 'destroy'])->name('admin.bookings.destroy');

    Route::get('/adminpanel/payments', [App\Http\Controllers\Admin\PaymentsController::class, 'index'])->name('admin.payments');
    Route::post('/adminpanel/payments', [App\Http\Controllers\Admin\PaymentsController::class, 'store'])->name('admin.payments.store');
    Route::put('/adminpanel/payments/{payment}', [App\Http\Controllers\Admin\PaymentsController::class, 'update'])->name('admin.payments.update');
    Route::delete('/adminpanel/payments/{payment}', [App\Http\Controllers\Admin\PaymentsController::class, 'destroy'])->name('admin.payments.destroy');

    Route::get('/adminpanel/nid', [App\Http\Controllers\Admin\NidController::class, 'index'])->name('admin.nid');

    Route::get('/adminpanel/food_items', [App\Http\Controllers\Admin\FoodItemsController::class, 'index'])->name('admin.food_items');
    Route::post('/adminpanel/food_items', [App\Http\Controllers\Admin\FoodItemsController::class, 'store'])->name('admin.food_items.store');
    Route::put('/adminpanel/food_items/{food_item}', [App\Http\Controllers\Admin\FoodItemsController::class, 'update'])->name('admin.food_items.update');
    Route::delete('/adminpanel/food_items/{food_item}', [App\Http\Controllers\Admin\FoodItemsController::class, 'destroy'])->name('admin.food_items.destroy');

    Route::get('/adminpanel/food_order', [App\Http\Controllers\Admin\FoodOrdersController::class, 'index'])->name('admin.food_order');
    Route::post('/adminpanel/food_order', [App\Http\Controllers\Admin\FoodOrdersController::class, 'store'])->name('admin.food_order.store');
    Route::put('/adminpanel/food_order/{food_order}', [App\Http\Controllers\Admin\FoodOrdersController::class, 'update'])->name('admin.food_order.update');
    Route::delete('/adminpanel/food_order/{food_order}', [App\Http\Controllers\Admin\FoodOrdersController::class, 'destroy'])->name('admin.food_order.destroy');
});





