<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Train;
use App\Models\Station;
use App\Models\Schedule;
use App\Models\Compartment;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Payment;
use App\Models\FoodItem;
use App\Models\FoodOrder;
use App\Models\TicketPrice;
use App\Models\NidDb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        // Users
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();

        // Core entities
        $totalTrains = Train::count();
        $totalStations = Station::count();
        $totalSchedules = Schedule::count();
        $totalCompartments = Compartment::count();
        $totalSeats = Seat::count();

        // Tickets & bookings
        $totalTickets = Ticket::count();
        $activeTickets = Ticket::whereIn('ticket_status', ['active'])
            ->count();

        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();

        // Payments
        $totalPayments = Payment::count();
        $completedPayments = Payment::where('payment_status', 'completed')->count();
        $pendingPayments = Payment::where('payment_status', 'pending')->count();
        $failedPayments = Payment::where('payment_status', 'failed')->count();
        $totalRevenue = Payment::where('payment_status', 'completed')->sum('amount');

        // Food
        $foodItems = FoodItem::count();
        $foodOrders = FoodOrder::count();

        // Recent activity (small samples)
        $recentBookings = Booking::with(['user', 'train'])
            ->latest('created_at')
            ->take(5)
            ->get();

        $recentPayments = Payment::with(['booking'])
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('admin.adminpanel', [
            'stats' => [
                'users' => $totalUsers,
                'admins' => $totalAdmins,
                'trains' => $totalTrains,
                'stations' => $totalStations,
                'schedules' => $totalSchedules,
                'compartments' => $totalCompartments,
                'seats' => $totalSeats,
                'tickets' => $totalTickets,
                'tickets_active' => $activeTickets,
                'bookings' => $totalBookings,
                'bookings_pending' => $pendingBookings,
                'bookings_confirmed' => $confirmedBookings,
                'bookings_cancelled' => $cancelledBookings,
                'payments' => $totalPayments,
                'payments_completed' => $completedPayments,
                'payments_pending' => $pendingPayments,
                'payments_failed' => $failedPayments,
                'revenue' => $totalRevenue,
                'food_items' => $foodItems,
                'food_orders' => $foodOrders,
            ],
            'recentBookings' => $recentBookings,
            'recentPayments' => $recentPayments,
        ]);
    }

    // Schedules
    public function schedules()
    {
        $schedules = Schedule::with(['train', 'sourceStation', 'destinationStation'])->orderByDesc('created_at')->get();
        $trains = Train::orderBy('train_name')->get(['train_id', 'train_name']);
        $stations = Station::orderBy('name')->get(['station_id', 'name']);
        return view('admin.schedule', compact('schedules', 'trains', 'stations'));
    }

    public function getSchedule($id)
    {
        $schedule = Schedule::with(['train', 'sourceStation', 'destinationStation'])->findOrFail($id);
        
        // Get ticket prices for this schedule's train by compartment class
        $ticketPrices = TicketPrice::whereHas('compartment', function($query) use ($schedule) {
            $query->where('train_id', $schedule->train_id);
        })->with('compartment')->get()->groupBy('compartment.class_name');
        
        // Extract prices with proper fallback
        $acPrice = 0;
        $shovanPrice = 0;
        $snigdhaPrice = 0;
        
        if ($ticketPrices->has('AC')) {
            $acPrice = $ticketPrices->get('AC')->first()->base_price ?? 0;
        }
        if ($ticketPrices->has('Shovan')) {
            $shovanPrice = $ticketPrices->get('Shovan')->first()->base_price ?? 0;
        }
        if ($ticketPrices->has('Snigdha')) {
            $snigdhaPrice = $ticketPrices->get('Snigdha')->first()->base_price ?? 0;
        }
        
        return response()->json([
            'schedule_id' => $schedule->schedule_id,
            'train_id' => $schedule->train_id,
            'train_name' => $schedule->train->train_name,
            'source_station_id' => $schedule->source_id,
            'destination_station_id' => $schedule->destination_id,
            'departure_time' => $schedule->departure_time->format('Y-m-d\TH:i'),
            'arrival_time' => $schedule->arrival_time->format('Y-m-d\TH:i'),
            'duration_minutes' => $schedule->duration,
            'status' => 'scheduled',
            'prices' => [
                'AC' => $acPrice,
                'Shovan' => $shovanPrice,
                'Snigdha' => $snigdhaPrice,
            ]
        ]);
    }

    public function storeSchedule(Request $request)
    {
        $validated = $request->validate([
            'train_id' => 'required|exists:trains,train_id',
            'source_station_id' => 'required|exists:stations,station_id',
            'destination_station_id' => 'required|exists:stations,station_id|different:source_station_id',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date|after:departure_time',
            'duration_minutes' => 'required|integer|min:1',
            'status' => 'required|in:scheduled,delayed,cancelled,arrived',
            'ac_price' => 'required|numeric|min:0',
            'shovan_price' => 'required|numeric|min:0',
            'snigdha_price' => 'required|numeric|min:0',
        ]);

        // Create schedule (using correct column names from migration)
        $schedule = Schedule::create([
            'train_id' => $validated['train_id'],
            'source_id' => $validated['source_station_id'],
            'destination_id' => $validated['destination_station_id'],
            'departure_time' => $validated['departure_time'],
            'arrival_time' => $validated['arrival_time'],
        ]);

        // Set ticket prices for each compartment class
        $this->setTicketPricesForSchedule($validated['train_id'], [
            'AC' => $validated['ac_price'],
            'Shovan' => $validated['shovan_price'],
            'Snigdha' => $validated['snigdha_price'],
        ]);

        return response()->json(['success' => true, 'message' => 'Schedule created successfully']);
    }

    public function updateSchedule(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        $validated = $request->validate([
            'train_id' => 'required|exists:trains,train_id',
            'source_station_id' => 'required|exists:stations,station_id',
            'destination_station_id' => 'required|exists:stations,station_id|different:source_station_id',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date|after:departure_time',
            'duration_minutes' => 'required|integer|min:1',
            'status' => 'required|in:scheduled,delayed,cancelled,arrived',
            'ac_price' => 'required|numeric|min:0',
            'shovan_price' => 'required|numeric|min:0',
            'snigdha_price' => 'required|numeric|min:0',
        ]);

        // Update schedule (using correct column names from migration)
        $schedule->update([
            'train_id' => $validated['train_id'],
            'source_id' => $validated['source_station_id'],
            'destination_id' => $validated['destination_station_id'],
            'departure_time' => $validated['departure_time'],
            'arrival_time' => $validated['arrival_time'],
        ]);

        // Update ticket prices
        $this->setTicketPricesForSchedule($validated['train_id'], [
            'AC' => $validated['ac_price'],
            'Shovan' => $validated['shovan_price'],
            'Snigdha' => $validated['snigdha_price'],
        ]);

        return response()->json(['success' => true, 'message' => 'Schedule updated successfully']);
    }

    public function destroySchedule($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        return response()->json(['success' => true, 'message' => 'Schedule deleted successfully']);
    }

    private function setTicketPricesForSchedule($trainId, $prices)
    {
        // Get compartments for this train grouped by class
        $compartments = Compartment::where('train_id', $trainId)->get()->groupBy('class_name');
        
        foreach ($prices as $className => $price) {
            $classCompartments = $compartments->get($className, collect());
            
            foreach ($classCompartments as $compartment) {
                TicketPrice::updateOrCreate(
                    [
                        'train_id' => $trainId,
                        'compartment_id' => $compartment->compartment_id,
                    ],
                    [
                        'base_price' => $price,
                    ]
                );
            }
        }
    }

    // Payments
    public function payments()
    {
        $payments = Payment::with(['booking.user'])->orderByDesc('created_at')->get();
        return view('admin.payments', compact('payments'));
    }

    public function getPayment($id)
    {
        $p = Payment::with('booking.user')->findOrFail($id);
        return response()->json([
            'payment_id' => $p->payment_id,
            'booking_id' => $p->booking_id,
            'amount' => $p->amount,
            'payment_method' => $p->payment_method,
            'payment_status' => $p->payment_status,
            'transaction_id' => $p->transaction_id,
            'paid_at' => optional($p->paid_at)->format('Y-m-d\TH:i'),
        ]);
    }

    public function storePayment(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,booking_id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:255',
            'payment_status' => 'required|in:completed,pending,failed',
            'transaction_id' => 'nullable|string|max:255',
            'paid_at' => 'nullable|date',
        ]);
        Payment::create($validated);
        return redirect()->route('admin.payments')->with('success', 'Payment created successfully');
    }

    public function updatePayment(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,booking_id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:255',
            'payment_status' => 'required|in:completed,pending,failed',
            'transaction_id' => 'nullable|string|max:255',
            'paid_at' => 'nullable|date',
        ]);
        $payment->update($validated);
        return redirect()->route('admin.payments')->with('success', 'Payment updated successfully');
    }

    public function destroyPayment($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return redirect()->route('admin.payments')->with('success', 'Payment deleted successfully');
    }

    // NID
    public function nid()
    {
        $nids = NidDb::orderByDesc('created_at')->get();
        return view('admin.nid', compact('nids'));
    }

    public function getNid($userId)
    {
        $nid = NidDb::findOrFail($userId);
        return response()->json([
            'user_id' => $nid->user_id,
            'nid_number' => $nid->nid_number,
            'name' => $nid->name,
            'dob' => optional($nid->dob)->format('Y-m-d'),
        ]);
    }

    public function storeNid(Request $request)
    {
        $validated = $request->validate([
            'nid_number' => 'required|string|unique:nid_db,nid_number',
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
        ]);

        // Generate auto-incrementing user_id
        $lastNid = NidDb::orderBy('user_id', 'desc')->first();
        $nextUserId = $lastNid ? $lastNid->user_id + 1 : 1;
        
        $validated['user_id'] = $nextUserId;

        NidDb::create($validated);
        return redirect()->route('admin.nid')->with('success', 'NID record created successfully');
    }

    public function updateNid(Request $request, $userId)
    {
        $nid = NidDb::findOrFail($userId);
        $validated = $request->validate([
            'nid_number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
        ]);
        $nid->update($validated);
        return redirect()->route('admin.nid')->with('success', 'NID record updated successfully');
    }

    public function destroyNid($userId)
    {
        $nid = NidDb::findOrFail($userId);
        $nid->delete();
        return redirect()->route('admin.nid')->with('success', 'NID record deleted successfully');
    }

    // Food Orders
    public function foodOrders()
    {
        $foodOrders = FoodOrder::with(['booking.user', 'foodItem'])->orderByDesc('created_at')->get();
        $bookings = Booking::with('user')->get(['booking_id', 'user_id']);
        $foodItems = FoodItem::get(['food_id', 'name', 'price']);
        return view('admin.food_order', compact('foodOrders', 'bookings', 'foodItems'));
    }

    public function getFoodOrder($id)
    {
        $order = FoodOrder::with(['booking.user', 'foodItem'])->findOrFail($id);
        return response()->json([
            'order_id' => $order->order_id,
            'booking_id' => $order->booking_id,
            'food_id' => $order->food_id,
            'quantity' => $order->quantity,
        ]);
    }

    public function storeFoodOrder(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,booking_id',
            'food_id' => 'required|exists:food_items,food_id',
            'quantity' => 'required|integer|min:1',
        ]);
        FoodOrder::create($validated);
        return redirect()->route('admin.food_order')->with('success', 'Food order created successfully');
    }

    public function updateFoodOrder(Request $request, $id)
    {
        $order = FoodOrder::findOrFail($id);
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,booking_id',
            'food_id' => 'required|exists:food_items,food_id',
            'quantity' => 'required|integer|min:1',
        ]);
        $order->update($validated);
        return redirect()->route('admin.food_order')->with('success', 'Food order updated successfully');
    }

    public function destroyFoodOrder($id)
    {
        $order = FoodOrder::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.food_order')->with('success', 'Food order deleted successfully');
    }

    // Seats
    public function seats()
    {
        $seats = Seat::with(['train', 'compartment'])->orderByDesc('created_at')->get();
        $trains = Train::orderBy('train_name')->get(['train_id', 'train_name']);
        $compartments = Compartment::orderBy('compartment_name')->get(['compartment_id', 'compartment_name', 'class_name', 'train_id']);
        return view('admin.seats', compact('seats', 'trains', 'compartments'));
    }

    public function getSeat($id)
    {
        $seat = Seat::with(['compartment', 'train'])->findOrFail($id);
        return response()->json([
            'seat_id' => $seat->seat_id,
            'train_id' => $seat->train_id,
            'compartment_id' => $seat->compartment_id,
            'seat_number' => $seat->seat_number,
            'is_available' => (bool) $seat->is_available,
        ]);
    }

    public function storeSeat(Request $request)
    {
        $validated = $request->validate([
            'train_id' => 'required|exists:trains,train_id',
            'compartment_id' => 'required|exists:compartments,compartment_id',
            'seat_number' => 'required|string|max:255',
            'is_available' => 'required|boolean',
        ]);
        Seat::create($validated);
        return redirect()->route('admin.seats')->with('success', 'Seat created successfully');
    }

    public function updateSeat(Request $request, $id)
    {
        $seat = Seat::findOrFail($id);
        $validated = $request->validate([
            'train_id' => 'required|exists:trains,train_id',
            'compartment_id' => 'required|exists:compartments,compartment_id',
            'seat_number' => 'required|string|max:255',
            'is_available' => 'required|boolean',
        ]);
        $seat->update($validated);
        return redirect()->route('admin.seats')->with('success', 'Seat updated successfully');
    }

    public function destroySeat($id)
    {
        $seat = Seat::findOrFail($id);
        $seat->delete();
        return redirect()->route('admin.seats')->with('success', 'Seat deleted successfully');
    }

    // Bookings
    public function bookings()
    {
        $bookings = Booking::with(['user', 'train'])->orderByDesc('created_at')->get();
        return view('admin.bookings', compact('bookings'));
    }

    public function getBooking($id)
    {
        $booking = Booking::with(['user', 'train'])->findOrFail($id);
        return response()->json([
            'booking_id' => $booking->booking_id,
            'user_id' => $booking->user_id,
            'user_name' => optional($booking->user)->name,
            'train_id' => $booking->train_id,
            'train_name' => optional($booking->train)->train_name,
            'booking_date' => optional($booking->booking_date)->format('Y-m-d\TH:i'),
            'status' => $booking->status,
            'total_amount' => $booking->total_amount,
        ]);
    }

    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'train_id' => 'required|exists:trains,train_id',
            'booking_date' => 'required|date',
            'status' => 'required|in:pending,confirmed,cancelled',
            'total_amount' => 'required|numeric|min:0',
        ]);
        Booking::create($validated);
        return redirect()->route('admin.bookings')->with('success', 'Booking created successfully');
    }

    public function updateBooking(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'train_id' => 'required|exists:trains,train_id',
            'booking_date' => 'required|date',
            'status' => 'required|in:pending,confirmed,cancelled',
            'total_amount' => 'required|numeric|min:0',
        ]);
        $booking->update($validated);
        return redirect()->route('admin.bookings')->with('success', 'Booking updated successfully');
    }

    public function destroyBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return redirect()->route('admin.bookings')->with('success', 'Booking deleted successfully');
    }

    // Compartments
    public function compartments()
    {
        $compartments = Compartment::with('train')->orderByDesc('created_at')->get();
        $trains = Train::orderBy('train_name')->get(['train_id', 'train_name']);
        return view('admin.compartments', compact('compartments', 'trains'));
    }

    public function getCompartment($id)
    {
        $compartment = Compartment::with('train')->findOrFail($id);
        return response()->json($compartment);
    }

    public function storeCompartment(Request $request)
    {
        $validated = $request->validate([
            'train_id' => 'required|exists:trains,train_id',
            'compartment_name' => 'required|string|max:255',
            'class_name' => 'required|string|max:255',
        ]);
        Compartment::create($validated);
        return redirect()->route('admin.compartments')->with('success', 'Compartment created successfully');
    }

    public function updateCompartment(Request $request, $id)
    {
        $compartment = Compartment::findOrFail($id);
        $validated = $request->validate([
            'train_id' => 'required|exists:trains,train_id',
            'compartment_name' => 'required|string|max:255',
            'class_name' => 'required|string|max:255',
        ]);
        $compartment->update($validated);
        return redirect()->route('admin.compartments')->with('success', 'Compartment updated successfully');
    }

    public function destroyCompartment($id)
    {
        $compartment = Compartment::findOrFail($id);
        $compartment->delete();
        return redirect()->route('admin.compartments')->with('success', 'Compartment deleted successfully');
    }

    // Food Items
    public function foodItems()
    {
        $foodItems = FoodItem::orderByDesc('created_at')->get();
        return view('admin.food_items', compact('foodItems'));
    }

    public function getFoodItem($id)
    {
        $food = FoodItem::findOrFail($id);
        return response()->json($food);
    }

    public function storeFoodItem(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'availability' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('food_images', 'public');
        }

        FoodItem::create($validated);
        return redirect()->route('admin.food_items')->with('success', 'Food item created successfully');
    }

    public function updateFoodItem(Request $request, $id)
    {
        $food = FoodItem::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'availability' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($food->image) {
                Storage::disk('public')->delete($food->image);
            }
            $validated['image'] = $request->file('image')->store('food_images', 'public');
        }

        $food->update($validated);
        return redirect()->route('admin.food_items')->with('success', 'Food item updated successfully');
    }

    public function destroyFoodItem($id)
    {
        $food = FoodItem::findOrFail($id);
        if ($food->image) {
            Storage::disk('public')->delete($food->image);
        }
        $food->delete();
        return redirect()->route('admin.food_items')->with('success', 'Food item deleted successfully');
    }
    // Users
    public function users()
    {
        $users = User::orderByDesc('created_at')->get();
        return view('admin.users', compact('users'));
    }

    public function getUser($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'user_id' => $user->user_id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'contact_number' => $user->contact_number,
            'address' => $user->address,
            'dob' => optional($user->dob)->format('Y-m-d'),
            'nid_number' => $user->nid_number,
            'nid_verified' => (bool) $user->nid_verified,
        ]);
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,passenger',
            'contact_number' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'dob' => 'required|date',
            'nid_number' => 'required|string|max:255',
            'nid_verified' => 'nullable|boolean',
            'password' => 'required|string|min:6',
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->contact_number = $validated['contact_number'] ?? null;
        $user->address = $validated['address'] ?? null;
        $user->dob = $validated['dob'];
        $user->nid_number = $validated['nid_number'];
        $user->nid_verified = (int) ($validated['nid_verified'] ?? 1);
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User created successfully');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'role' => 'required|in:admin,passenger',
            'contact_number' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'dob' => 'required|date',
            'nid_number' => 'required|string|max:255',
            'nid_verified' => 'nullable|boolean',
            'password' => 'nullable|string|min:6',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->contact_number = $validated['contact_number'] ?? null;
        $user->address = $validated['address'] ?? null;
        $user->dob = $validated['dob'];
        $user->nid_number = $validated['nid_number'];
        $user->nid_verified = (int) ($validated['nid_verified'] ?? $user->nid_verified);
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User updated successfully');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        if (auth()->id() === $user->user_id) {
            return redirect()->back()->with('error', 'You cannot delete your own account');
        }
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully');
    }

    // Trains
    public function trains()
    {
        $trains = Train::orderByDesc('created_at')->get();
        return view('admin.trains', compact('trains'));
    }

    public function getTrain($id)
    {
        $train = Train::findOrFail($id);
        return response()->json($train);
    }

    public function storeTrain(Request $request)
    {
        $validated = $request->validate([
            'train_name' => 'required|string|max:255',
            'total_seats' => 'required|integer|min:1',
            'train_type' => 'required|in:Intercity,Express,Local',
        ]);

        // Create the train
        $train = Train::create($validated);
        
        // Auto-generate compartments and seats
        $this->generateCompartmentsAndSeats($train->train_id);
        
        return redirect()->route('admin.trains')->with('success', 'Train created successfully with compartments and seats');
    }

    /**
     * Auto-generate compartments and seats for a new train
     */
    private function generateCompartmentsAndSeats($trainId)
    {
        // Define compartment structure
        $compartments = [
            ['name' => 'Ka', 'class' => 'AC', 'seats' => 8],
            ['name' => 'Kha', 'class' => 'AC', 'seats' => 8],
            ['name' => 'Ga', 'class' => 'Shovan', 'seats' => 18],
            ['name' => 'Gha', 'class' => 'Shovan', 'seats' => 18],
            ['name' => 'Uma', 'class' => 'Snigdha', 'seats' => 24],
            ['name' => 'Cha', 'class' => 'Snigdha', 'seats' => 24],
        ];

        foreach ($compartments as $compData) {
            // Create compartment
            $compartment = Compartment::create([
                'train_id' => $trainId,
                'compartment_name' => $compData['name'],
                'class_name' => $compData['class']
            ]);

            // Generate seats for this compartment
            for ($i = 1; $i <= $compData['seats']; $i++) {
                Seat::create([
                    'train_id' => $trainId,
                    'compartment_id' => $compartment->compartment_id,
                    'seat_number' => (string)$i,
                    'is_available' => true
                ]);
            }
        }
    }

    public function updateTrain(Request $request, $id)
    {
        $train = Train::findOrFail($id);
        $validated = $request->validate([
            'train_name' => 'required|string|max:255',
            'total_seats' => 'required|integer|min:1',
            'train_type' => 'required|in:Intercity,Express,Local',
        ]);

        $train->update($validated);
        return redirect()->route('admin.trains')->with('success', 'Train updated successfully');
    }

    public function destroyTrain($id)
    {
        $train = Train::findOrFail($id);
        $train->delete();
        return redirect()->route('admin.trains')->with('success', 'Train deleted successfully');
    }

    // Stations
    public function stations()
    {
        $stations = Station::orderByDesc('created_at')->get();
        return view('admin.stations', compact('stations'));
    }

    public function getStation($id)
    {
        $station = Station::findOrFail($id);
        return response()->json($station);
    }

    public function storeStation(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);
        Station::create($validated);
        return redirect()->route('admin.stations')->with('success', 'Station created successfully');
    }

    public function updateStation(Request $request, $id)
    {
        $station = Station::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);
        $station->update($validated);
        return redirect()->route('admin.stations')->with('success', 'Station updated successfully');
    }

    public function destroyStation($id)
    {
        $station = Station::findOrFail($id);
        $station->delete();
        return redirect()->route('admin.stations')->with('success', 'Station deleted successfully');
    }

    // Tickets
    public function tickets()
    {
        $tickets = Ticket::with(['booking.user', 'seat', 'compartment'])->orderByDesc('created_at')->get();
        return view('admin.tickets', compact('tickets'));
    }

    public function getTicket($id)
    {
        $ticket = Ticket::with(['compartment'])->findOrFail($id);
        return response()->json([
            'ticket_id' => $ticket->ticket_id,
            'booking_id' => $ticket->booking_id,
            'compartment_id' => $ticket->compartment_id,
            'seat_id' => $ticket->seat_id,
            'travel_date' => optional($ticket->travel_date)->format('Y-m-d'),
            'ticket_status' => $ticket->ticket_status,
            'compartment' => [
                'class_name' => optional($ticket->compartment)->class_name,
            ],
        ]);
    }

    public function storeTicket(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,booking_id',
            'compartment_id' => 'required|exists:compartments,compartment_id',
            'seat_id' => 'required|exists:seats,seat_id',
            'travel_date' => 'required|date',
            'ticket_status' => 'required|in:active,cancelled,used',
        ]);
        Ticket::create($validated);
        return redirect()->route('admin.tickets')->with('success', 'Ticket created successfully');
    }

    public function updateTicket(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,booking_id',
            'compartment_id' => 'required|exists:compartments,compartment_id',
            'seat_id' => 'required|exists:seats,seat_id',
            'travel_date' => 'required|date',
            'ticket_status' => 'required|in:active,cancelled,used',
        ]);
        $ticket->update($validated);
        return redirect()->route('admin.tickets')->with('success', 'Ticket updated successfully');
    }

    public function destroyTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return redirect()->route('admin.tickets')->with('success', 'Ticket deleted successfully');
    }

    // Ticket Prices
    public function ticketPrices()
    {
        $ticketPrices = TicketPrice::with(['train', 'compartment'])->orderByDesc('created_at')->get();
        return view('admin.ticket_prices', compact('ticketPrices'));
    }

    public function getTicketPrice($id)
    {
        $price = TicketPrice::with(['compartment'])->findOrFail($id);
        return response()->json([
            'price_id' => $price->price_id,
            'train_id' => $price->train_id,
            'compartment_id' => $price->compartment_id,
            'base_price' => $price->base_price,
            'compartment' => [
                'class_name' => optional($price->compartment)->class_name,
            ],
        ]);
    }

    public function storeTicketPrice(Request $request)
    {
        $validated = $request->validate([
            'train_id' => 'required|exists:trains,train_id',
            'compartment_id' => 'required|exists:compartments,compartment_id',
            'base_price' => 'required|numeric|min:0',
        ]);
        TicketPrice::create($validated);
        return redirect()->route('admin.ticket_prices')->with('success', 'Ticket price created successfully');
    }

    public function updateTicketPrice(Request $request, $id)
    {
        $price = TicketPrice::findOrFail($id);
        $validated = $request->validate([
            'train_id' => 'required|exists:trains,train_id',
            'compartment_id' => 'required|exists:compartments,compartment_id',
            'base_price' => 'required|numeric|min:0',
        ]);
        $price->update($validated);
        return redirect()->route('admin.ticket_prices')->with('success', 'Ticket price updated successfully');
    }

    public function destroyTicketPrice($id)
    {
        $price = TicketPrice::findOrFail($id);
        $price->delete();
        return redirect()->route('admin.ticket_prices')->with('success', 'Ticket price deleted successfully');
    }

    // API helpers for dropdowns
    public function getBookings()
    {
        $bookings = Booking::with(['user:user_id,name'])
            ->latest()
            ->get(['booking_id', 'user_id']);
        return response()->json($bookings);
    }

    public function getCompartments()
    {
        return response()->json(
            Compartment::orderBy('compartment_name')
                ->get(['compartment_id', 'compartment_name', 'class_name'])
        );
    }

    public function getSeats()
    {
        return response()->json(
            Seat::orderBy('seat_number')
                ->get(['seat_id', 'seat_number', 'compartment_id'])
        );
    }

    public function getTrains()
    {
        return response()->json(
            Train::orderBy('train_name')
                ->get(['train_id', 'train_name'])
        );
    }
}
