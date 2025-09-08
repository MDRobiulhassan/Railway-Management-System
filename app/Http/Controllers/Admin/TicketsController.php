<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Booking;
use App\Models\Train;
use App\Models\Seat;
use App\Models\Compartment;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['booking.user', 'train', 'seat', 'compartment'])
            ->orderByDesc('ticket_id')->paginate(20);
        return view('admin.tickets', compact('tickets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'exists:bookings,booking_id'],
            'train_id' => ['required', 'exists:trains,train_id'],
            'seat_id' => ['required', 'exists:seats,seat_id'],
            'compartment_id' => ['required', 'exists:compartments,compartment_id'],
            'travel_date' => ['required', 'date'],
            'ticket_status' => ['required', 'in:active,cancelled,used'],
        ]);
        Ticket::create($validated);
        return redirect()->route('admin.tickets')->with('success', 'Ticket created');
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'exists:bookings,booking_id'],
            'train_id' => ['required', 'exists:trains,train_id'],
            'seat_id' => ['required', 'exists:seats,seat_id'],
            'compartment_id' => ['required', 'exists:compartments,compartment_id'],
            'travel_date' => ['required', 'date'],
            'ticket_status' => ['required', 'in:active,cancelled,used'],
        ]);
        $ticket->update($validated);
        return redirect()->route('admin.tickets')->with('success', 'Ticket updated');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('admin.tickets')->with('success', 'Ticket deleted');
    }
}


