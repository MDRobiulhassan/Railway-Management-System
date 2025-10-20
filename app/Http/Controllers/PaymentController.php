<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Payment;
use App\Models\FoodOrder;
use App\Models\FoodItem;
use App\Models\Schedule;
use App\Models\TicketPrice;

class PaymentController extends Controller
{
    /**
     * Initiate payment with SSLCommerz
     */
    public function initiatePayment(Request $request)
    {
        Log::info('Payment initiation started', ['user_id' => Auth::id()]);

        if (!Auth::check()) {
            Log::warning('Payment initiation failed - user not authenticated');
            return redirect()->route('login.form')->with('error', 'Please login to continue');
        }

        // Get booking data from session
        $bookingData = $this->getBookingDataFromSession();
        
        if (!$bookingData) {
            Log::error('Payment initiation failed - booking session expired', ['user_id' => Auth::id()]);
            return back()->with('error', 'Booking session expired. Please start booking again.');
        }

        Log::info('Booking data retrieved', [
            'user_id' => Auth::id(),
            'schedule_id' => $bookingData['schedule']->schedule_id ?? 'N/A',
            'seats_count' => count($bookingData['selected_seats'] ?? [])
        ]);

        // Calculate amounts
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

        // Generate unique transaction ID
        $transactionId = 'TXN' . time() . rand(1000, 9999);
        
        // Store transaction ID and user ID in session for validation
        session(['payment.transaction_id' => $transactionId]);
        session(['payment.amount' => $totalAmount]);
        session(['payment.user_id' => Auth::id()]);

        // Store booking data in cache with transaction ID (more reliable than session for redirects)
        $cacheKey = 'booking_data.' . $transactionId;
        $bookingDataToStore = [
            'schedule_id' => $bookingData['schedule']->schedule_id,
            'selected_seats' => $bookingData['selected_seats'],
            'class' => $bookingData['class'],
            'compartment_id' => $bookingData['compartment_id'],
            'food_orders' => $bookingData['food_orders'] ?? [],
            'user_id' => Auth::id(),
            'created_at' => now()->timestamp
        ];
        
        // Store in cache for 2 hours
        Cache::put($cacheKey, $bookingDataToStore, now()->addHours(2));
        
        // Also keep in session as backup
        session([$cacheKey => $bookingDataToStore]);
        session()->save();
        
        // Verify storage
        $verification = Cache::has($cacheKey) ? 'SUCCESS' : 'FAILED';
        
        Log::info('Booking data stored in cache', [
            'transaction_id' => $transactionId,
            'cache_key' => $cacheKey,
            'session_id' => session()->getId(),
            'data_stored' => $bookingDataToStore,
            'cache_verification' => $verification,
            'cache_driver' => config('cache.default')
        ]);

        // Prepare SSLCommerz payment data
        $postData = [
            'store_id' => config('sslcommerz.store_id'),
            'store_passwd' => config('sslcommerz.store_password'),
            'total_amount' => $totalAmount,
            'currency' => 'BDT',
            'tran_id' => $transactionId,
            'success_url' => route('payment.success') . '?tran_id=' . $transactionId,
            'fail_url' => route('payment.fail') . '?tran_id=' . $transactionId,
            'cancel_url' => route('payment.cancel') . '?tran_id=' . $transactionId,
            'ipn_url' => route('payment.ipn'),
            
            // Customer information
            'cus_name' => Auth::user()->name,
            'cus_email' => Auth::user()->email,
            'cus_phone' => Auth::user()->phone ?? '01700000000',
            'cus_add1' => 'Dhaka',
            'cus_city' => 'Dhaka',
            'cus_country' => 'Bangladesh',
            
            // Product information
            'product_name' => 'Railway Ticket Booking',
            'product_category' => 'Transport',
            'product_profile' => 'general',
            
            // Shipping information
            'shipping_method' => 'NO',
            'num_of_item' => count($bookingData['selected_seats']),
            
            // Custom fields to pass transaction ID
            'value_a' => $transactionId,
            'value_b' => Auth::id(),
        ];

        // Get SSLCommerz API URL
        $apiUrl = config('sslcommerz.mode') === 'live' 
            ? config('sslcommerz.live_url') . '/gwprocess/v4/api.php'
            : config('sslcommerz.sandbox_url') . '/gwprocess/v4/api.php';

        try {
            Log::info('Sending request to SSLCommerz', [
                'api_url' => $apiUrl,
                'transaction_id' => $transactionId,
                'amount' => $totalAmount
            ]);

            // Send request to SSLCommerz
            // For local development, disable SSL verification
            $response = Http::timeout(30)
                ->withOptions([
                    'verify' => config('app.env') === 'production'
                ])
                ->asForm()
                ->post($apiUrl, $postData);
            
            if (!$response->successful()) {
                Log::error('SSLCommerz HTTP Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return back()->with('error', 'Payment gateway connection failed. Please try again.');
            }

            $responseData = $response->json();
            
            Log::info('SSLCommerz Response', ['response' => $responseData]);

            if (isset($responseData['status']) && $responseData['status'] === 'SUCCESS') {
                Log::info('Payment gateway redirect', ['url' => $responseData['GatewayPageURL']]);
                // Redirect to payment gateway
                return redirect($responseData['GatewayPageURL']);
            } else {
                $errorMessage = $responseData['failedreason'] ?? $responseData['status'] ?? 'Payment initiation failed';
                Log::error('SSLCommerz Error', [
                    'error' => $errorMessage,
                    'full_response' => $responseData
                ]);
                return back()->with('error', 'Payment initiation failed: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Payment Initiation Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            return back()->with('error', 'Payment gateway error: ' . $e->getMessage());
        }
    }

    /**
     * Handle successful payment
     */
    public function success(Request $request)
    {
        // Start session if not already started
        if (!session()->isStarted()) {
            session()->start();
        }

        Log::info('Payment success callback received', [
            'transaction_id' => $request->tran_id,
            'amount' => $request->amount,
            'status' => $request->status,
            'session_id' => session()->getId(),
            'all_request_data' => $request->all(),
            'cookies' => $request->cookies->all(),
            'session_driver' => config('session.driver'),
            'db_connection' => config('database.default')
        ]);

        $transactionId = $request->tran_id;
        $amount = $request->amount;
        $status = $request->status;
        $valId = $request->val_id;

        // Validate transaction
        Log::info('Validating transaction', ['transaction_id' => $transactionId]);
        
        if (!$this->validateTransaction($transactionId, $amount, $valId)) {
            Log::error('Transaction validation failed', [
                'transaction_id' => $transactionId,
                'amount' => $amount
            ]);
            return view('payment.fail', [
                'transactionId' => $transactionId,
                'message' => 'Payment validation failed. Please contact support.'
            ]);
        }

        Log::info('Transaction validated successfully');

        // Get booking data from cache using transaction ID (fallback to session)
        $cacheKey = 'booking_data.' . $transactionId;
        $storedData = Cache::get($cacheKey);
        
        // Fallback to session if not in cache
        if (!$storedData) {
            Log::warning('Data not in cache, trying session', ['transaction_id' => $transactionId]);
            $storedData = session($cacheKey);
        }
        
        if (!$storedData) {
            Log::error('Booking data not found in cache or session', [
                'transaction_id' => $transactionId,
                'cache_key' => $cacheKey,
                'cache_has_key' => Cache::has($cacheKey),
                'session_has_key' => session()->has($cacheKey),
                'cache_driver' => config('cache.default')
            ]);
            return view('payment.fail', [
                'transactionId' => $transactionId,
                'message' => 'Booking data expired. Please contact support with transaction ID: ' . $transactionId
            ]);
        }

        Log::info('Booking data retrieved successfully', [
            'source' => Cache::has($cacheKey) ? 'cache' : 'session',
            'data' => $storedData
        ]);

        // Reconstruct booking data
        $schedule = Schedule::with(['train', 'sourceStation', 'destinationStation'])
                           ->find($storedData['schedule_id']);
        
        if (!$schedule) {
            Log::error('Schedule not found', ['schedule_id' => $storedData['schedule_id']]);
            return view('payment.fail', [
                'transactionId' => $transactionId,
                'message' => 'Schedule not found. Please contact support.'
            ]);
        }

        $bookingData = [
            'schedule' => $schedule,
            'selected_seats' => $storedData['selected_seats'],
            'class' => $storedData['class'],
            'compartment_id' => $storedData['compartment_id'],
            'food_orders' => $storedData['food_orders'] ?? []
        ];

        $userId = $storedData['user_id'];

        // Re-authenticate the user if not already authenticated
        if (!Auth::check()) {
            $user = \App\Models\User::find($userId);
            if ($user) {
                Auth::login($user);
                session()->regenerate(); // Regenerate session to prevent fixation
                Log::info('User re-authenticated', ['user_id' => $userId]);
            } else {
                Log::error('User not found for re-authentication', ['user_id' => $userId]);
            }
        }

        Log::info('Booking data reconstructed successfully');

        try {
            DB::beginTransaction();

            // Calculate amounts
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

            // Create booking (userId already retrieved from stored data)
            $booking = Booking::create([
                'user_id' => $userId,
                'train_id' => $bookingData['schedule']->train_id,
                'booking_date' => now(),
                'total_amount' => $totalAmount,
                'status' => 'confirmed'
            ]);

            // Create tickets
            foreach ($bookingData['selected_seats'] as $seatId) {
                Ticket::create([
                    'booking_id' => $booking->booking_id,
                    'train_id' => $bookingData['schedule']->train_id,
                    'seat_id' => $seatId,
                    'travel_date' => $bookingData['schedule']->departure_time->format('Y-m-d'),
                    'compartment_id' => $bookingData['compartment_id'],
                    'ticket_status' => 'active'
                ]);
            }

            // Create food orders
            if (!empty($bookingData['food_orders'])) {
                foreach ($bookingData['food_orders'] as $foodOrder) {
                    FoodOrder::create([
                        'booking_id' => $booking->booking_id,
                        'food_id' => $foodOrder['food_id'],
                        'quantity' => $foodOrder['quantity']
                    ]);
                }
            }

            // Create payment record
            Payment::create([
                'booking_id' => $booking->booking_id,
                'amount' => $totalAmount,
                'payment_method' => 'SSLCommerz',
                'payment_status' => 'completed',
                'transaction_id' => $transactionId,
                'paid_at' => now()
            ]);

            DB::commit();

            Log::info('Booking created successfully', [
                'booking_id' => $booking->booking_id,
                'user_id' => $userId,
                'transaction_id' => $transactionId
            ]);

            // Clear booking session
            $this->clearBookingSession($transactionId);

            Log::info('Redirecting to user dashboard');

            // Store success message in session
            session()->flash('success', 'Payment successful! Your booking is confirmed.');
            session()->flash('booking_id', $booking->booking_id);

            // Show success page with auto-redirect
            return view('payment.success', [
                'bookingId' => $booking->booking_id,
                'message' => 'Payment successful! Your booking is confirmed.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Booking Creation Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => session('payment.user_id'),
                'transaction_id' => $transactionId
            ]);
            return view('payment.fail', [
                'transactionId' => $transactionId,
                'message' => 'Booking failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Handle failed payment
     */
    public function fail(Request $request)
    {
        $transactionId = $request->tran_id;
        
        Log::warning('Payment Failed', [
            'transaction_id' => $transactionId,
            'user_id' => Auth::id()
        ]);

        return view('payment.fail', [
            'transactionId' => $transactionId,
            'message' => 'Payment failed. Please try again.'
        ]);
    }

    /**
     * Handle cancelled payment
     */
    public function cancel(Request $request)
    {
        $transactionId = $request->tran_id;
        
        Log::info('Payment Cancelled', [
            'transaction_id' => $transactionId,
            'user_id' => Auth::id()
        ]);

        return view('payment.cancel', [
            'transactionId' => $transactionId,
            'message' => 'Payment cancelled by user.'
        ]);
    }

    /**
     * Handle IPN (Instant Payment Notification)
     */
    public function ipn(Request $request)
    {
        $transactionId = $request->tran_id;
        $status = $request->status;
        
        Log::info('IPN Received', [
            'transaction_id' => $transactionId,
            'status' => $status,
            'data' => $request->all()
        ]);

        // Update payment status if needed
        if ($status === 'VALID' || $status === 'VALIDATED') {
            Payment::where('transaction_id', $transactionId)
                   ->update(['payment_status' => 'completed']);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Validate transaction with SSLCommerz
     */
    private function validateTransaction($transactionId, $amount, $valId)
    {
        $validationUrl = config('sslcommerz.mode') === 'live'
            ? config('sslcommerz.live_url') . '/validator/api/validationserverAPI.php'
            : config('sslcommerz.sandbox_url') . '/validator/api/validationserverAPI.php';

        try {
            $response = Http::withOptions([
                    'verify' => config('app.env') === 'production'
                ])
                ->get($validationUrl, [
                    'val_id' => $valId,
                    'store_id' => config('sslcommerz.store_id'),
                    'store_passwd' => config('sslcommerz.store_password'),
                    'format' => 'json'
                ]);

            $validationData = $response->json();

            Log::info('Transaction Validation', ['data' => $validationData]);

            if (isset($validationData['status']) && $validationData['status'] === 'VALID') {
                return $validationData['tran_id'] === $transactionId 
                    && (float)$validationData['amount'] === (float)$amount;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Validation Error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Get booking data from session
     */
    private function getBookingDataFromSession()
    {
        $scheduleId = session('booking.schedule_id');
        $selectedSeats = session('booking.selected_seats');
        $class = session('booking.class');
        $compartmentId = session('booking.compartment_id');
        $foodOrders = session('booking.food_orders', []);

        if (!$scheduleId || !$selectedSeats || !$class || !$compartmentId) {
            return null;
        }

        $schedule = Schedule::with(['train', 'sourceStation', 'destinationStation'])->find($scheduleId);
        
        if (!$schedule) {
            return null;
        }

        return [
            'schedule' => $schedule,
            'selected_seats' => $selectedSeats,
            'class' => $class,
            'compartment_id' => $compartmentId,
            'food_orders' => $foodOrders
        ];
    }

    /**
     * Calculate seat price
     */
    private function calculateSeatPrice($bookingData)
    {
        $ticketPrice = TicketPrice::where('train_id', $bookingData['schedule']->train_id)
                                 ->where('compartment_id', $bookingData['compartment_id'])
                                 ->first();

        $basePrice = $ticketPrice ? $ticketPrice->base_price : 350;
        return $basePrice * count($bookingData['selected_seats']);
    }

    /**
     * Clear booking session
     */
    private function clearBookingSession($transactionId = null)
    {
        session()->forget([
            'booking.schedule_id',
            'booking.schedule',
            'booking.selected_seats',
            'booking.class',
            'booking.compartment_id',
            'booking.food_orders',
            'payment.transaction_id',
            'payment.amount',
            'payment.user_id'
        ]);
        
        // Clear transaction-specific booking data from both cache and session
        if ($transactionId) {
            $cacheKey = 'booking_data.' . $transactionId;
            Cache::forget($cacheKey);
            session()->forget($cacheKey);
            
            Log::info('Cleared booking data', [
                'transaction_id' => $transactionId,
                'cache_key' => $cacheKey
            ]);
        }
    }
}
