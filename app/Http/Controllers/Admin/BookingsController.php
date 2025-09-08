<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'train'])->orderByDesc('booking_id')->paginate(20);
        return view('admin.bookings', compact('bookings'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,user_id'],
            'train_id' => ['required', 'exists:trains,train_id'],
            'booking_date' => ['required', 'date'],
            'status' => ['required', 'in:pending,confirmed,cancelled'],
            'total_amount' => ['required', 'numeric', 'min:0'],
        ]);
        $booking->update($validated);
        return redirect()->route('admin.bookings')->with('success', 'Booking updated');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings')->with('success', 'Booking deleted');
    }
}


