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
Route::middleware(['auth'])->group(function () {
    Route::post('/booking-start', [App\Http\Controllers\BookingController::class, 'start'])->name('booking.start');
    Route::get('/booking-step1', [App\Http\Controllers\BookingController::class, 'step1'])->name('booking.step1');
    Route::post('/booking-step2', [App\Http\Controllers\BookingController::class, 'step2'])->name('booking.step2');
    Route::post('/booking-step3', [App\Http\Controllers\BookingController::class, 'step3'])->name('booking.step3');
    Route::post('/booking-confirm', [App\Http\Controllers\BookingController::class, 'confirm'])->name('booking.confirm');
    Route::get('/booking-confirm', [App\Http\Controllers\BookingController::class, 'confirmGet'])->name('booking.confirm.get');
    Route::post('/booking-finalize', [App\Http\Controllers\BookingController::class, 'finalize'])->name('booking.finalize');
});

// user routes
Route::middleware(['auth'])->group(function () {
    Route::get('/user_dashboard', [App\Http\Controllers\UserDashboardController::class, 'index'])->name('user.dashboard');

    Route::get('/user_profile', [App\Http\Controllers\UserProfileController::class, 'show'])->name('user.profile');
    Route::post('/user_profile', [App\Http\Controllers\UserProfileController::class, 'update'])->name('user.profile.update');
});

// Ticket routes
Route::middleware(['auth'])->group(function () {
    Route::get('/tickets/{booking}', [App\Http\Controllers\TicketController::class, 'show'])->name('ticket.view');
    Route::get('/tickets/{booking}/download', [App\Http\Controllers\TicketController::class, 'download'])->name('ticket.download');
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Schedules
    Route::get('/schedules', [App\Http\Controllers\AdminController::class, 'schedules'])->name('schedules');
    Route::get('/schedules/{id}', [App\Http\Controllers\AdminController::class, 'getSchedule'])->name('schedules.get');
    Route::post('/schedules', [App\Http\Controllers\AdminController::class, 'storeSchedule'])->name('schedules.store');
    Route::put('/schedules/{id}', [App\Http\Controllers\AdminController::class, 'updateSchedule'])->name('schedules.update');
    Route::delete('/schedules/{id}', [App\Http\Controllers\AdminController::class, 'destroySchedule'])->name('schedules.destroy');
    
    // Payments
    Route::get('/payments', [App\Http\Controllers\AdminController::class, 'payments'])->name('payments');
    Route::get('/payments/{id}', [App\Http\Controllers\AdminController::class, 'getPayment'])->name('payments.get');
    Route::post('/payments', [App\Http\Controllers\AdminController::class, 'storePayment'])->name('payments.store');
    Route::put('/payments/{id}', [App\Http\Controllers\AdminController::class, 'updatePayment'])->name('payments.update');
    Route::delete('/payments/{id}', [App\Http\Controllers\AdminController::class, 'destroyPayment'])->name('payments.destroy');
});

// admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/adminpanel', [App\Http\Controllers\AdminController::class, 'index'])->name('adminpanel');

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

    Route::get('/adminpanel/schedule', [App\Http\Controllers\AdminController::class, 'schedules'])->name('admin.schedule');
    Route::post('/adminpanel/schedule', [App\Http\Controllers\AdminController::class, 'storeSchedule'])->name('admin.schedule.store');
    Route::put('/adminpanel/schedule/{id}', [App\Http\Controllers\AdminController::class, 'updateSchedule'])->name('admin.schedule.update');
    Route::delete('/adminpanel/schedule/{id}', [App\Http\Controllers\AdminController::class, 'destroySchedule'])->name('admin.schedule.destroy');
    Route::get('/adminpanel/schedule/{id}/edit', [App\Http\Controllers\AdminController::class, 'getSchedule'])->name('admin.schedule.edit');

    Route::get('/adminpanel/compartments', [App\Http\Controllers\AdminController::class, 'compartments'])->name('admin.compartments');
    Route::post('/adminpanel/compartments', [App\Http\Controllers\AdminController::class, 'storeCompartment'])->name('admin.compartments.store');
    Route::put('/adminpanel/compartments/{id}', [App\Http\Controllers\AdminController::class, 'updateCompartment'])->name('admin.compartments.update');
    Route::delete('/adminpanel/compartments/{id}', [App\Http\Controllers\AdminController::class, 'destroyCompartment'])->name('admin.compartments.destroy');
    Route::get('/adminpanel/compartments/{id}/edit', [App\Http\Controllers\AdminController::class, 'getCompartment'])->name('admin.compartments.edit');

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

    Route::get('/adminpanel/seats', [App\Http\Controllers\AdminController::class, 'seats'])->name('admin.seats');
    Route::post('/adminpanel/seats', [App\Http\Controllers\AdminController::class, 'storeSeat'])->name('admin.seats.store');
    Route::put('/adminpanel/seats/{id}', [App\Http\Controllers\AdminController::class, 'updateSeat'])->name('admin.seats.update');
    Route::delete('/adminpanel/seats/{id}', [App\Http\Controllers\AdminController::class, 'destroySeat'])->name('admin.seats.destroy');
    Route::get('/adminpanel/seats/{id}/edit', [App\Http\Controllers\AdminController::class, 'getSeat'])->name('admin.seats.edit');

    Route::get('/adminpanel/bookings', [App\Http\Controllers\AdminController::class, 'bookings'])->name('admin.bookings');
    Route::post('/adminpanel/bookings', [App\Http\Controllers\AdminController::class, 'storeBooking'])->name('admin.bookings.store');
    Route::put('/adminpanel/bookings/{id}', [App\Http\Controllers\AdminController::class, 'updateBooking'])->name('admin.bookings.update');
    Route::delete('/adminpanel/bookings/{id}', [App\Http\Controllers\AdminController::class, 'destroyBooking'])->name('admin.bookings.destroy');
    Route::get('/adminpanel/bookings/{id}/edit', [App\Http\Controllers\AdminController::class, 'getBooking'])->name('admin.bookings.edit');

    Route::get('/adminpanel/payments', [App\Http\Controllers\AdminController::class, 'payments'])->name('admin.payments');
    Route::post('/adminpanel/payments', [App\Http\Controllers\AdminController::class, 'storePayment'])->name('admin.payments.store');
    Route::put('/adminpanel/payments/{id}', [App\Http\Controllers\AdminController::class, 'updatePayment'])->name('admin.payments.update');
    Route::delete('/adminpanel/payments/{id}', [App\Http\Controllers\AdminController::class, 'destroyPayment'])->name('admin.payments.destroy');
    Route::get('/adminpanel/payments/{id}/edit', [App\Http\Controllers\AdminController::class, 'getPayment'])->name('admin.payments.edit');

    Route::get('/adminpanel/nid', [App\Http\Controllers\AdminController::class, 'nid'])->name('admin.nid');
    Route::post('/adminpanel/nid', [App\Http\Controllers\AdminController::class, 'storeNid'])->name('admin.nid.store');
    Route::put('/adminpanel/nid/{userId}', [App\Http\Controllers\AdminController::class, 'updateNid'])->name('admin.nid.update');
    Route::delete('/adminpanel/nid/{userId}', [App\Http\Controllers\AdminController::class, 'destroyNid'])->name('admin.nid.destroy');
    Route::get('/adminpanel/nid/{userId}/edit', [App\Http\Controllers\AdminController::class, 'getNid'])->name('admin.nid.edit');

    Route::get('/adminpanel/food_items', [App\Http\Controllers\AdminController::class, 'foodItems'])->name('admin.food_items');
    Route::post('/adminpanel/food_items', [App\Http\Controllers\AdminController::class, 'storeFoodItem'])->name('admin.food_items.store');
    Route::post('/adminpanel/food_items/{id}', [App\Http\Controllers\AdminController::class, 'updateFoodItem'])->name('admin.food_items.update');
    Route::delete('/adminpanel/food_items/{id}', [App\Http\Controllers\AdminController::class, 'destroyFoodItem'])->name('admin.food_items.destroy');
    Route::get('/adminpanel/food_items/{id}/edit', [App\Http\Controllers\AdminController::class, 'getFoodItem'])->name('admin.food_items.edit');

    Route::get('/adminpanel/food_order', [App\Http\Controllers\AdminController::class, 'foodOrders'])->name('admin.food_order');
    Route::post('/adminpanel/food_order', [App\Http\Controllers\AdminController::class, 'storeFoodOrder'])->name('admin.food_order.store');
    Route::put('/adminpanel/food_order/{id}', [App\Http\Controllers\AdminController::class, 'updateFoodOrder'])->name('admin.food_order.update');
    Route::delete('/adminpanel/food_order/{id}', [App\Http\Controllers\AdminController::class, 'destroyFoodOrder'])->name('admin.food_order.destroy');
    Route::get('/adminpanel/food_order/{id}/edit', [App\Http\Controllers\AdminController::class, 'getFoodOrder'])->name('admin.food_order.edit');
});





