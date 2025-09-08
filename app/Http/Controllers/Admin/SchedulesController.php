<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Train;
use App\Models\Station;
use Illuminate\Http\Request;

class SchedulesController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['train', 'sourceStation', 'destinationStation'])->orderByDesc('schedule_id')->paginate(20);
        $trains = Train::orderBy('train_name')->get(['train_id', 'train_name']);
        $stations = Station::orderBy('name')->get(['station_id', 'name']);
        return view('admin.schedule', compact('schedules', 'trains', 'stations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'train_id' => ['required', 'exists:trains,train_id'],
            'source_id' => ['required', 'exists:stations,station_id'],
            'destination_id' => ['required', 'exists:stations,station_id'],
            'arrival_time' => ['required', 'date'],
            'departure_time' => ['required', 'date'],
        ]);
        Schedule::create($validated);
        return redirect()->route('admin.schedule')->with('success', 'Schedule added');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'train_id' => ['required', 'exists:trains,train_id'],
            'source_id' => ['required', 'exists:stations,station_id'],
            'destination_id' => ['required', 'exists:stations,station_id'],
            'arrival_time' => ['required', 'date'],
            'departure_time' => ['required', 'date'],
        ]);
        $schedule->update($validated);
        return redirect()->route('admin.schedule')->with('success', 'Schedule updated');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedule')->with('success', 'Schedule deleted');
    }
}


