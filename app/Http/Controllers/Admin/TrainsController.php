<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Train;
use Illuminate\Http\Request;

class TrainsController extends Controller
{
    public function index()
    {
        $trains = Train::orderByDesc('train_id')->paginate(20);
        return view('admin.trains', compact('trains'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'train_name' => ['required', 'string', 'max:255'],
            'train_type' => ['required', 'string', 'max:50'],
            'total_seats' => ['required', 'integer', 'min:1'],
        ]);
        Train::create($validated);
        return redirect()->route('admin.trains')->with('success', 'Train added');
    }

    public function update(Request $request, Train $train)
    {
        $validated = $request->validate([
            'train_name' => ['required', 'string', 'max:255'],
            'train_type' => ['required', 'string', 'max:50'],
            'total_seats' => ['required', 'integer', 'min:1'],
        ]);
        $train->update($validated);
        return redirect()->route('admin.trains')->with('success', 'Train updated');
    }

    public function destroy(Train $train)
    {
        $train->delete();
        return redirect()->route('admin.trains')->with('success', 'Train deleted');
    }
}


