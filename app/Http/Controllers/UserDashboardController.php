<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

class UserDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $bookings = Booking::with([
            'train',
            'tickets.seat',
            'tickets.compartment',
        ])->where('user_id', $userId)
          ->orderByDesc('booking_date')
          ->get();

        return view('user_dashboard', compact('bookings'));
    }
}



