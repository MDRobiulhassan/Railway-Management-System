<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

class UserDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        $allBookings = Booking::with([
            'train',
            'tickets.seat',
            'tickets.compartment',
            'payment'
        ])->where('user_id', $userId)
          ->orderByDesc('booking_date')
          ->get();

        $today = now()->startOfDay();
        
        $upcomingBookings = $allBookings->filter(function ($booking) use ($today) {
            $firstTicket = $booking->tickets->first();
            $travelDate = $firstTicket?->travel_date;
            return $travelDate && $travelDate->startOfDay()->gte($today);
        });

        $pastBookings = $allBookings->filter(function ($booking) use ($today) {
            $firstTicket = $booking->tickets->first();
            $travelDate = $firstTicket?->travel_date;
            return $travelDate && $travelDate->startOfDay()->lt($today);
        });

        return view('user_dashboard', compact('upcomingBookings', 'pastBookings'));
    }
}



