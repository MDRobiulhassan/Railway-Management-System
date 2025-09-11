<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Train;
use App\Models\Ticket;
use App\Models\Booking;
use App\Models\Compartment;
use App\Models\Seat;
use App\Models\Station;
use App\Models\TicketPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display all stations
     */
    public function stations()
    {
        $stations = Station::orderBy('created_at', 'desc')->get();
        return view('admin.stations', compact('stations'));
    }

    /**
     * Store a newly created station
     */
    public function storeStation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        Station::create([
            'name' => $request->name,
            'location' => $request->location,
        ]);

        return redirect()->route('admin.stations')->with('success', 'Station created successfully!');
    }

    /**
     * Update the specified station
     */
    public function updateStation(Request $request, $id)
    {
        $station = Station::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $station->update([
            'name' => $request->name,
            'location' => $request->location,
        ]);

        return redirect()->route('admin.stations')->with('success', 'Station updated successfully!');
    }

    /**
     * Remove the specified station
     */
    public function destroyStation($id)
    {
        $station = Station::findOrFail($id);

        // Prevent deletion if referenced by schedules
        if ($station->sourceSchedules()->exists() || $station->destinationSchedules()->exists()) {
            return redirect()->route('admin.stations')->with('error', 'Cannot delete station referenced in schedules!');
        }

        $station->delete();

        return redirect()->route('admin.stations')->with('success', 'Station deleted successfully!');
    }

    /**
     * Get station data for editing
     */
    public function getStation($id)
    {
        $station = Station::findOrFail($id);
        return response()->json($station);
    }
    /**
     * Display all users
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    /**
     * Store a newly created user
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'role' => 'required|in:passenger,admin',
            'dob' => 'required|date|before:today',
            'nid_number' => 'required|string|unique:users,nid_number',
            'nid_verified' => 'required|boolean',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'role' => $request->role,
            'dob' => $request->dob,
            'nid_number' => $request->nid_number,
            'nid_verified' => $request->nid_verified,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    /**
     * Update the specified user
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'role' => 'required|in:passenger,admin',
            'dob' => 'required|date|before:today',
            'nid_number' => 'required|string|unique:users,nid_number,' . $user->user_id . ',user_id',
            'nid_verified' => 'required|boolean',
            'password' => 'nullable|string|min:8',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'role' => $request->role,
            'dob' => $request->dob,
            'nid_number' => $request->nid_number,
            'nid_verified' => $request->nid_verified,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user
     */
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() == $user->user_id) {
            return redirect()->route('admin.users')->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

    /**
     * Get user data for editing
     */
    public function getUser($id)
    {
        $user = User::findOrFail($id);
        
        // Format the date for HTML date input
        $userData = $user->toArray();
        if ($user->dob) {
            $userData['dob'] = $user->dob->format('Y-m-d');
        }
        
        return response()->json($userData);
    }

    /**
     * Display all trains
     */
    public function trains()
    {
        $trains = Train::orderBy('created_at', 'desc')->get();
        return view('admin.trains', compact('trains'));
    }

    /**
     * Store a newly created train
     */
    public function storeTrain(Request $request)
    {
        $request->validate([
            'train_name' => 'required|string|max:255',
            'train_type' => 'required|string|max:50',
            'total_seats' => 'required|integer|min:1',
        ]);

        Train::create([
            'train_name' => $request->train_name,
            'train_type' => $request->train_type,
            'total_seats' => $request->total_seats,
        ]);

        return redirect()->route('admin.trains')->with('success', 'Train created successfully!');
    }

    /**
     * Update the specified train
     */
    public function updateTrain(Request $request, $id)
    {
        $train = Train::findOrFail($id);

        $request->validate([
            'train_name' => 'required|string|max:255',
            'train_type' => 'required|string|max:50',
            'total_seats' => 'required|integer|min:1',
        ]);

        $train->update([
            'train_name' => $request->train_name,
            'train_type' => $request->train_type,
            'total_seats' => $request->total_seats,
        ]);

        return redirect()->route('admin.trains')->with('success', 'Train updated successfully!');
    }

    /**
     * Remove the specified train
     */
    public function destroyTrain($id)
    {
        $train = Train::findOrFail($id);

        // Check if train has any schedules
        if ($train->schedules()->count() > 0) {
            return redirect()->route('admin.trains')->with('error', 'Cannot delete train with existing schedules!');
        }

        $train->delete();

        return redirect()->route('admin.trains')->with('success', 'Train deleted successfully!');
    }

    /**
     * Get train data for editing
     */
    public function getTrain($id)
    {
        $train = Train::findOrFail($id);
        return response()->json($train);
    }

    /**
     * Display all tickets
     */
    public function tickets()
    {
        $tickets = Ticket::with(['booking.user', 'train', 'seat', 'compartment'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.tickets', compact('tickets'));
    }

    /**
     * Store a newly created ticket
     */
    public function storeTicket(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,booking_id',
            'seat_id' => 'required|exists:seats,seat_id',
            'compartment_id' => 'required|exists:compartments,compartment_id',
            'travel_date' => 'required|date|after_or_equal:today',
            'ticket_status' => 'required|in:active,cancelled,used',
        ]);

        // Get train_id from booking
        $booking = Booking::findOrFail($request->booking_id);
        
        Ticket::create([
            'booking_id' => $request->booking_id,
            'train_id' => $booking->train_id,
            'seat_id' => $request->seat_id,
            'compartment_id' => $request->compartment_id,
            'travel_date' => $request->travel_date,
            'ticket_status' => $request->ticket_status,
        ]);

        return redirect()->route('admin.tickets')->with('success', 'Ticket created successfully!');
    }

    /**
     * Update the specified ticket
     */
    public function updateTicket(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'seat_id' => 'required|exists:seats,seat_id',
            'compartment_id' => 'required|exists:compartments,compartment_id',
            'travel_date' => 'required|date|after_or_equal:today',
            'ticket_status' => 'required|in:active,cancelled,used',
        ]);

        $ticket->update([
            'seat_id' => $request->seat_id,
            'compartment_id' => $request->compartment_id,
            'travel_date' => $request->travel_date,
            'ticket_status' => $request->ticket_status,
        ]);

        return redirect()->route('admin.tickets')->with('success', 'Ticket updated successfully!');
    }

    /**
     * Remove the specified ticket
     */
    public function destroyTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('admin.tickets')->with('success', 'Ticket deleted successfully!');
    }

    /**
     * Get ticket data for editing
     */
    public function getTicket($id)
    {
        $ticket = Ticket::with(['booking.user', 'train', 'seat', 'compartment'])->findOrFail($id);
        
        $ticketData = $ticket->toArray();
        if ($ticket->travel_date) {
            $ticketData['travel_date'] = $ticket->travel_date->format('Y-m-d');
        }
        
        return response()->json($ticketData);
    }

    /**
     * Get bookings for dropdown
     */
    public function getBookings()
    {
        $bookings = Booking::with('user')->get();
        return response()->json($bookings);
    }

    /**
     * Get compartments for dropdown
     */
    public function getCompartments()
    {
        $compartments = Compartment::with('train')->get();
        return response()->json($compartments);
    }

    /**
     * Get seats for dropdown
     */
    public function getSeats()
    {
        $seats = Seat::with('compartment')->get();
        return response()->json($seats);
    }

    /**
     * Get trains for dropdown
     */
    public function getTrains()
    {
        $trains = Train::all();
        return response()->json($trains);
    }

    /**
     * Display all ticket prices
     */
    public function ticketPrices()
    {
        $ticketPrices = TicketPrice::with(['train', 'compartment'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.ticket_prices', compact('ticketPrices'));
    }

    /**
     * Store a newly created ticket price
     */
    public function storeTicketPrice(Request $request)
    {
        $request->validate([
            'train_id' => 'required|exists:trains,train_id',
            'compartment_id' => 'required|exists:compartments,compartment_id',
            'base_price' => 'required|numeric|min:0',
        ]);

        // Check if price already exists for this train and compartment
        $existingPrice = TicketPrice::where('train_id', $request->train_id)
            ->where('compartment_id', $request->compartment_id)
            ->first();

        if ($existingPrice) {
            return redirect()->route('admin.ticket_prices')->with('error', 'Price already exists for this train and compartment combination!');
        }

        TicketPrice::create([
            'train_id' => $request->train_id,
            'compartment_id' => $request->compartment_id,
            'base_price' => $request->base_price,
        ]);

        return redirect()->route('admin.ticket_prices')->with('success', 'Ticket price created successfully!');
    }

    /**
     * Update the specified ticket price
     */
    public function updateTicketPrice(Request $request, $id)
    {
        $ticketPrice = TicketPrice::findOrFail($id);

        $request->validate([
            'train_id' => 'required|exists:trains,train_id',
            'compartment_id' => 'required|exists:compartments,compartment_id',
            'base_price' => 'required|numeric|min:0',
        ]);

        // Check if price already exists for this train and compartment (excluding current record)
        $existingPrice = TicketPrice::where('train_id', $request->train_id)
            ->where('compartment_id', $request->compartment_id)
            ->where('price_id', '!=', $id)
            ->first();

        if ($existingPrice) {
            return redirect()->route('admin.ticket_prices')->with('error', 'Price already exists for this train and compartment combination!');
        }

        $ticketPrice->update([
            'train_id' => $request->train_id,
            'compartment_id' => $request->compartment_id,
            'base_price' => $request->base_price,
        ]);

        return redirect()->route('admin.ticket_prices')->with('success', 'Ticket price updated successfully!');
    }

    /**
     * Remove the specified ticket price
     */
    public function destroyTicketPrice($id)
    {
        $ticketPrice = TicketPrice::findOrFail($id);
        $ticketPrice->delete();

        return redirect()->route('admin.ticket_prices')->with('success', 'Ticket price deleted successfully!');
    }

    /**
     * Get ticket price data for editing
     */
    public function getTicketPrice($id)
    {
        $ticketPrice = TicketPrice::with(['train', 'compartment'])->findOrFail($id);
        return response()->json($ticketPrice);
    }
}
