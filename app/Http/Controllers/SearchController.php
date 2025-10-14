<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Train;
use App\Models\Station;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function index()
    {
        $stations = Station::orderBy('name')->get();
        return view('search', compact('stations'));
    }

    public function searchResults(Request $request)
    {
        $query = Schedule::with(['train', 'sourceStation', 'destinationStation']);
        
        $query->where('departure_time', '>', now());
        
        // Filter by source station
        if ($request->has('from') && !empty($request->from)) {
            $query->whereHas('sourceStation', function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->from}%");
            });
        }

        // Filter by destination station
        if ($request->has('to') && !empty($request->to)) {
            $query->whereHas('destinationStation', function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->to}%");
            });
        }

        // Filter by date
        if ($request->has('date') && !empty($request->date)) {
            $query->whereDate('departure_time', $request->date);
        }

        // Filter by class 
        $selectedClass = $request->get('class');

        $schedules = $query->orderBy('departure_time', 'asc')->paginate(10);
        
        $searchParams = [
            'from' => $request->get('from'),
            'to' => $request->get('to'),
            'date' => $request->get('date'),
            'class' => $selectedClass
        ];

        return view('search_result', compact('schedules', 'searchParams'));
    }
}
