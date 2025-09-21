<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Train;
use App\Models\Compartment;
use App\Models\Seat;
use App\Models\FoodItem;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Payment;
use App\Models\FoodOrder;
use App\Models\TicketPrice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Start booking process by storing schedule in session
     */
    public function start(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,schedule_id'
        ]);

        // Get schedule with train and station details
        $schedule = Schedule::with(['train', 'sourceStation', 'destinationStation'])
                           ->findOrFail($request->schedule_id);

        // Check if user already has 5 tickets for this schedule
        if (Auth::check()) {
            $existingTickets = Ticket::join('bookings', 'tickets.booking_id', '=', 'bookings.booking_id')
                ->where('bookings.user_id', Auth::id())
                ->where('tickets.train_id', $schedule->train_id)
                ->where('tickets.travel_date', $schedule->departure_time->format('Y-m-d'))
                ->where('tickets.ticket_status', '!=', 'cancelled')
                ->count();
            
            if ($existingTickets >= 5) {
                return redirect()->back()->with('error', 
                    'You have already booked the maximum of 5 tickets for this journey.'
                );
            }
        }

        // Store schedule in session
        session(['booking.schedule_id' => $request->schedule_id]);
        session(['booking.schedule' => $schedule]);

        return redirect()->route('booking.step1');
    }

    /**
     * Step 1: Seat Selection
     */
    public function step1()
    {
        $scheduleId = session('booking.schedule_id');
        if (!$scheduleId) {
            return redirect()->route('schedule')->with('error', 'Please select a schedule first');
        }

        $schedule = session('booking.schedule');
        if (!$schedule) {
            return redirect()->route('schedule')->with('error', 'Session expired, please select schedule again');
        }

        // Get compartments for this train
        $compartments = Compartment::where('train_id', $schedule->train_id)
                                 ->orderBy('class_name')
                                 ->get()
                                 ->groupBy('class_name');

        // Get seats for this train with availability
        $seats = Seat::where('train_id', $schedule->train_id)
                    ->with('compartment')
                    ->get();

        // Get booked seats for this specific schedule date
        $bookedSeats = DB::table('tickets')
                        ->where('tickets.train_id', $schedule->train_id)
                        ->where('tickets.travel_date', $schedule->departure_time->format('Y-m-d'))
                        ->where('tickets.ticket_status', '!=', 'cancelled')
                        ->pluck('tickets.seat_id')
                        ->toArray();

        // Get ticket prices for different classes
        $ticketPrices = TicketPrice::where('train_id', $schedule->train_id)
                                 ->with('compartment')
                                 ->get()
                                 ->keyBy('compartment.class_name');

        // Get user's existing tickets for this schedule
        $existingTickets = 0;
        if (Auth::check()) {
            $existingTickets = Ticket::join('bookings', 'tickets.booking_id', '=', 'bookings.booking_id')
                ->where('bookings.user_id', Auth::id())
                ->where('tickets.train_id', $schedule->train_id)
                ->where('tickets.travel_date', $schedule->departure_time->format('Y-m-d'))
                ->where('tickets.ticket_status', '!=', 'cancelled')
                ->count();
        }

        return view('booking_step1', compact('schedule', 'compartments', 'seats', 'bookedSeats', 'ticketPrices', 'existingTickets'));
    }

    /**
     * Process seat selection and move to Step 2: Food Selection
     */
    public function step2(Request $request)
    {
        $request->validate([
            'selected_seats' => 'required|array|min:1|max:5',
            'selected_seats.*' => 'exists:seats,seat_id',
            'class' => 'required|string',
            'compartment_id' => 'required|exists:compartments,compartment_id'
        ]);

        // Check if user already has tickets for this schedule
        $scheduleId = session('booking.schedule_id');
        $schedule = session('booking.schedule');
        
        if (Auth::check()) {
            $existingTickets = Ticket::join('bookings', 'tickets.booking_id', '=', 'bookings.booking_id')
                ->where('bookings.user_id', Auth::id())
                ->where('tickets.train_id', $schedule->train_id)
                ->where('tickets.travel_date', $schedule->departure_time->format('Y-m-d'))
                ->where('tickets.ticket_status', '!=', 'cancelled')
                ->count();
            
            $newTicketCount = count($request->selected_seats);
            $totalTickets = $existingTickets + $newTicketCount;
            
            if ($totalTickets > 5) {
                return redirect()->back()->with('error', 
                    "You can only book maximum 5 tickets per schedule. You already have {$existingTickets} ticket(s) for this journey."
                );
            }
        }

        // Store seat selection in session
        session(['booking.selected_seats' => $request->selected_seats]);
        session(['booking.class' => $request->class]);
        session(['booking.compartment_id' => $request->compartment_id]);

        // Get food items grouped by category
        $foodItems = FoodItem::where('availability', true)
                            ->orderBy('category')
                            ->orderBy('name')
                            ->get()
                            ->groupBy('category');

        return view('booking_step2', compact('foodItems'));
    }

    /**
     * Process food selection and move to Step 3: Payment
     */
    public function step3(Request $request)
    {
        // Store food selection in session (optional)
        $foodOrders = [];
        if ($request->has('food_items')) {
            foreach ($request->food_items as $foodId => $quantity) {
                if ($quantity > 0) {
                    $foodOrders[] = [
                        'food_id' => $foodId,
                        'quantity' => $quantity
                    ];
                }
            }
        }
        
        session(['booking.food_orders' => $foodOrders]);

        return view('booking_step3');
    }

    /**
     * Process payment method and show confirmation
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|in:bKash,Nagad,Card'
        ]);

        // Store payment method in session
        session(['booking.payment_method' => $request->payment_method]);

        // Get all booking data from session
        $bookingData = $this->getBookingDataFromSession();

        if (!$bookingData) {
            return redirect()->route('schedule')->with('error', 'Booking session expired');
        }

        // Debug: Log the request to see what's happening
        \Log::info('Booking confirm accessed', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'payment_method' => $request->payment_method
        ]);

        return view('booking_confirm', compact('bookingData'));
    }

    /**
     * Handle GET requests to booking-confirm (redirect to proper flow)
     */
    public function confirmGet(Request $request)
    {
        // Check if there's valid booking data in session
        $bookingData = $this->getBookingDataFromSession();
        
        if (!$bookingData) {
            return redirect()->route('schedule')->with('error', 'Please start a new booking');
        }

        // If payment method is not set, redirect to step 3
        if (!isset($bookingData['payment_method'])) {
            return redirect()->route('booking.step3')->with('error', 'Please select a payment method');
        }

        // If we have complete booking data, show confirmation
        return view('booking_confirm', compact('bookingData'));
    }

    /**
     * Finalize booking and save to database
     */
    public function finalize(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Please login to complete booking');
        }

        $bookingData = $this->getBookingDataFromSession();
        
        if (!$bookingData) {
            return redirect()->route('schedule')->with('error', 'Booking session expired');
        }

        try {
            DB::beginTransaction();

            // Calculate total amount first
            $seatPrice = $this->calculateSeatPrice($bookingData);
            $foodPrice = 0;
            
            if (!empty($bookingData['food_orders'])) {
                foreach ($bookingData['food_orders'] as $foodOrder) {
                    $foodItem = FoodItem::find($foodOrder['food_id']);
                    if ($foodItem) {
                        $foodPrice += $foodItem->price * $foodOrder['quantity'];
                    }
                }
            }
            
            $totalAmount = $seatPrice + $foodPrice;

            // Create booking record
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'train_id' => $bookingData['schedule']->train_id,
                'booking_date' => now(),
                'total_amount' => $totalAmount,
                'status' => 'confirmed'
            ]);

            // Create tickets for each selected seat and update seat availability
            foreach ($bookingData['selected_seats'] as $seatId) {
                Ticket::create([
                    'booking_id' => $booking->booking_id,
                    'train_id' => $bookingData['schedule']->train_id,
                    'seat_id' => $seatId,
                    'travel_date' => $bookingData['schedule']->departure_time->format('Y-m-d'),
                    'compartment_id' => $bookingData['compartment_id'],
                    'ticket_status' => 'active'
                ]);
                
                // Note: We don't update seat availability in seats table as it's managed per schedule/date
                // The availability is calculated dynamically based on bookings for specific dates
            }

            // Create food orders if any (food price already calculated above)
            if (!empty($bookingData['food_orders'])) {
                foreach ($bookingData['food_orders'] as $foodOrder) {
                    FoodOrder::create([
                        'booking_id' => $booking->booking_id,
                        'food_id' => $foodOrder['food_id'],
                        'quantity' => $foodOrder['quantity']
                    ]);
                }
            }

            // Generate unique transaction ID
            $transactionId = $this->generateTransactionId($bookingData['payment_method'], $booking->booking_id);

            // Create payment record
            Payment::create([
                'booking_id' => $booking->booking_id,
                'amount' => $totalAmount,
                'payment_method' => $bookingData['payment_method'],
                'payment_status' => 'completed',
                'transaction_id' => $transactionId,
                'paid_at' => now()
            ]);

            DB::commit();

            // Clear booking session
            $this->clearBookingSession();

            return redirect()->route('user.dashboard')->with('success', 'Booking confirmed successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Booking failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'booking_data' => $bookingData
            ]);
            return back()->with('error', 'Booking failed: ' . $e->getMessage());
        }
    }

    /**
     * Get all booking data from session
     */
    private function getBookingDataFromSession()
    {
        $scheduleId = session('booking.schedule_id');
        $selectedSeats = session('booking.selected_seats');
        $class = session('booking.class');
        $compartmentId = session('booking.compartment_id');
        $foodOrders = session('booking.food_orders', []);
        $paymentMethod = session('booking.payment_method');

        if (!$scheduleId || !$selectedSeats || !$class || !$compartmentId) {
            return null;
        }

        // Load schedule with relationships
        $schedule = Schedule::with(['train', 'sourceStation', 'destinationStation'])->find($scheduleId);
        
        if (!$schedule) {
            return null;
        }

        return [
            'schedule' => $schedule,
            'selected_seats' => $selectedSeats,
            'class' => $class,
            'compartment_id' => $compartmentId,
            'food_orders' => $foodOrders,
            'payment_method' => $paymentMethod
        ];
    }

    /**
     * Calculate seat price based on class and number of seats
     */
    private function calculateSeatPrice($bookingData)
    {
        $ticketPrice = TicketPrice::where('train_id', $bookingData['schedule']->train_id)
                                 ->where('compartment_id', $bookingData['compartment_id'])
                                 ->first();

        $basePrice = $ticketPrice ? $ticketPrice->base_price : 350; // Default price
        return $basePrice * count($bookingData['selected_seats']);
    }

    /**
     * Generate unique transaction ID based on payment method
     */
    private function generateTransactionId($paymentMethod, $bookingId)
    {
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        
        // Create payment method prefix
        $methodPrefix = match($paymentMethod) {
            'bKash' => 'BK',
            'Nagad' => 'NG', 
            'Card' => 'CD',
            default => 'PM'
        };
        
        // Format: METHOD_TIMESTAMP_BOOKINGID_RANDOM
        // Example: BK_20241122143052_1234_A1B2C3
        return $methodPrefix . '_' . $timestamp . '_' . $bookingId . '_' . $random;
    }

    /**
     * Clear booking session data
     */
    private function clearBookingSession()
    {
        session()->forget([
            'booking.schedule_id',
            'booking.schedule',
            'booking.selected_seats',
            'booking.class',
            'booking.compartment_id',
            'booking.food_orders',
            'booking.payment_method'
        ]);
    }
}
