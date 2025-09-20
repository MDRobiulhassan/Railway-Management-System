<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Train;
use App\Models\Station;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['train', 'sourceStation', 'destinationStation']);
        
        // Only show future schedules
        $query->where('departure_time', '>', now());
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('train', function($q) use ($search) {
                $q->where('train_name', 'LIKE', "%{$search}%");
            })->orWhereHas('sourceStation', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%");
            })->orWhereHas('destinationStation', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%");
            });
        }

        // Filter by source station
        if ($request->has('from') && !empty($request->from)) {
            $query->whereHas('sourceStation', function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->from}%")
                  ->orWhere('location', 'LIKE', "%{$request->from}%");
            });
        }

        // Filter by destination station
        if ($request->has('to') && !empty($request->to)) {
            $query->whereHas('destinationStation', function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->to}%")
                  ->orWhere('location', 'LIKE', "%{$request->to}%");
            });
        }

        // Filter by date
        if ($request->has('date') && !empty($request->date)) {
            $query->whereDate('departure_time', $request->date);
        }

        $schedules = $query->orderBy('departure_time', 'asc')->paginate(10);
        
        // Get all stations for search dropdowns
        $stations = Station::orderBy('name')->get();

        return view('schedule', compact('schedules', 'stations'));
    }

    public function search(Request $request)
    {
        $query = Schedule::with(['train', 'sourceStation', 'destinationStation']);
        
        // Only show future schedules
        $query->where('departure_time', '>', now());
        
        if ($request->has('from') && !empty($request->from)) {
            $query->whereHas('sourceStation', function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->from}%");
            });
        }

        if ($request->has('to') && !empty($request->to)) {
            $query->whereHas('destinationStation', function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->to}%");
            });
        }

        if ($request->has('date') && !empty($request->date)) {
            $query->whereDate('departure_time', $request->date);
        }

        $schedules = $query->orderBy('departure_time', 'asc')->get();

        return response()->json([
            'success' => true,
            'schedules' => $schedules
        ]);
    }
}
