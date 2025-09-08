<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seat;
use App\Models\Compartment;
use App\Models\Train;
use Illuminate\Http\Request;

class SeatsController extends Controller
{
    public function index()
    {
        $seats = Seat::with(['train', 'compartment'])->orderByDesc('seat_id')->paginate(30);
        $trains = Train::orderBy('train_name')->get(['train_id', 'train_name']);
        $compartments = Compartment::orderBy('compartment_name')->get(['compartment_id', 'compartment_name', 'train_id']);
        return view('admin.seats', compact('seats', 'trains', 'compartments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'train_id' => ['required', 'exists:trains,train_id'],
            'compartment_id' => ['required', 'exists:compartments,compartment_id'],
            'seat_number' => ['required', 'string', 'max:10'],
            'is_available' => ['required', 'boolean'],
        ]);
        Seat::create($validated);
        return redirect()->route('admin.seats')->with('success', 'Seat added');
    }

    public function update(Request $request, Seat $seat)
    {
        $validated = $request->validate([
            'train_id' => ['required', 'exists:trains,train_id'],
            'compartment_id' => ['required', 'exists:compartments,compartment_id'],
            'seat_number' => ['required', 'string', 'max:10'],
            'is_available' => ['required', 'boolean'],
        ]);
        $seat->update($validated);
        return redirect()->route('admin.seats')->with('success', 'Seat updated');
    }

    public function destroy(Seat $seat)
    {
        $seat->delete();
        return redirect()->route('admin.seats')->with('success', 'Seat deleted');
    }
}


