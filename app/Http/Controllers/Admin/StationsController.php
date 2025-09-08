<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\Request;

class StationsController extends Controller
{
    public function index()
    {
        $stations = Station::orderBy('name')->paginate(20);
        return view('admin.stations', compact('stations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
        ]);

        Station::create($validated);
        return redirect()->route('admin.stations')->with('success', 'Station created');
    }

    public function update(Request $request, Station $station)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
        ]);
        $station->update($validated);
        return redirect()->route('admin.stations')->with('success', 'Station updated');
    }

    public function destroy(Station $station)
    {
        $station->delete();
        return redirect()->route('admin.stations')->with('success', 'Station deleted');
    }
}


