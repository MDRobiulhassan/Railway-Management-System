<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Compartment;
use App\Models\Train;
use Illuminate\Http\Request;

class CompartmentsController extends Controller
{
    public function index()
    {
        $compartments = Compartment::with('train')->orderByDesc('compartment_id')->paginate(20);
        $trains = Train::orderBy('train_name')->get(['train_id', 'train_name']);
        return view('admin.compartments', compact('compartments', 'trains'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'train_id' => ['required', 'exists:trains,train_id'],
            'compartment_name' => ['required', 'string', 'max:10'],
            'class_name' => ['required', 'string', 'max:50'],
        ]);
        Compartment::create($validated);
        return redirect()->route('admin.compartments')->with('success', 'Compartment added');
    }

    public function update(Request $request, Compartment $compartment)
    {
        $validated = $request->validate([
            'train_id' => ['required', 'exists:trains,train_id'],
            'compartment_name' => ['required', 'string', 'max:10'],
            'class_name' => ['required', 'string', 'max:50'],
        ]);
        $compartment->update($validated);
        return redirect()->route('admin.compartments')->with('success', 'Compartment updated');
    }

    public function destroy(Compartment $compartment)
    {
        $compartment->delete();
        return redirect()->route('admin.compartments')->with('success', 'Compartment deleted');
    }
}


