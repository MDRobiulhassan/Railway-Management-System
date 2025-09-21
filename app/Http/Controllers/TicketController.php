<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    public function show($bookingId)
    {
        $booking = Booking::with([
            'user',
            'train',
            'tickets.seat',
            'tickets.compartment',
            'tickets',
            'payment'
        ])->findOrFail($bookingId);

        if (Auth::id() !== $booking->user_id && !(Auth::user() && Auth::user()->role === 'admin')) {
            abort(403);
        }

        // Resolve schedule for this booking by train and travel date
        $firstTravelDate = $booking->tickets->first()?->travel_date;
        $schedule = null;
        if ($firstTravelDate) {
            $schedule = Schedule::with(['sourceStation', 'destinationStation'])
                ->where('train_id', $booking->train_id)
                ->whereDate('departure_time', $firstTravelDate)
                ->orderBy('departure_time', 'asc')
                ->first();
        }

        return view('tickets.view_ticket', compact('booking', 'schedule'));
    }

    public function download($bookingId)
    {
        $booking = Booking::with([
            'user',
            'train',
            'tickets.seat',
            'tickets.compartment',
            'tickets',
            'payment'
        ])->findOrFail($bookingId);

        if (Auth::id() !== $booking->user_id && !(Auth::user() && Auth::user()->role === 'admin')) {
            abort(403);
        }

        // Resolve schedule for this booking by train and travel date
        $firstTravelDate = $booking->tickets->first()?->travel_date;
        $schedule = null;
        if ($firstTravelDate) {
            $schedule = Schedule::with(['sourceStation', 'destinationStation'])
                ->where('train_id', $booking->train_id)
                ->whereDate('departure_time', $firstTravelDate)
                ->orderBy('departure_time', 'asc')
                ->first();
        }

        $pdf = Pdf::loadView('tickets.ticket_pdf', compact('booking', 'schedule'));
        $filename = 'ticket_booking_' . $booking->booking_id . '.pdf';
        return $pdf->download($filename);
    }
}



