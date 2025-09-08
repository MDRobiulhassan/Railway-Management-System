<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketPrice;
use App\Models\Train;
use App\Models\Compartment;
use Illuminate\Http\Request;

class TicketPricesController extends Controller
{
    public function index()
    {
        $prices = TicketPrice::with(['train', 'compartment'])->orderByDesc('price_id')->paginate(20);
        $trains = Train::orderBy('train_name')->get(['train_id', 'train_name']);
        $compartments = Compartment::orderBy('compartment_name')->get(['compartment_id', 'compartment_name', 'train_id']);
        return view('admin.ticket_prices', compact('prices', 'trains', 'compartments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'train_id' => ['required', 'exists:trains,train_id'],
            'compartment_id' => ['required', 'exists:compartments,compartment_id'],
            'base_price' => ['required', 'numeric', 'min:0'],
        ]);
        TicketPrice::create($validated);
        return redirect()->route('admin.ticket_prices')->with('success', 'Ticket price added');
    }

    public function update(Request $request, TicketPrice $ticket_price)
    {
        $validated = $request->validate([
            'train_id' => ['required', 'exists:trains,train_id'],
            'compartment_id' => ['required', 'exists:compartments,compartment_id'],
            'base_price' => ['required', 'numeric', 'min:0'],
        ]);
        $ticket_price->update($validated);
        return redirect()->route('admin.ticket_prices')->with('success', 'Ticket price updated');
    }

    public function destroy(TicketPrice $ticket_price)
    {
        $ticket_price->delete();
        return redirect()->route('admin.ticket_prices')->with('success', 'Ticket price deleted');
    }
}


