<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['booking.user'])->orderByDesc('payment_id')->paginate(20);
        return view('admin.payments', compact('payments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'exists:bookings,booking_id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:50'],
            'payment_status' => ['required', 'in:pending,completed,failed'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'paid_at' => ['nullable', 'date'],
        ]);
        Payment::create($validated);
        return redirect()->route('admin.payments')->with('success', 'Payment added');
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'exists:bookings,booking_id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:50'],
            'payment_status' => ['required', 'in:pending,completed,failed'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'paid_at' => ['nullable', 'date'],
        ]);
        $payment->update($validated);
        return redirect()->route('admin.payments')->with('success', 'Payment updated');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments')->with('success', 'Payment deleted');
    }
}


