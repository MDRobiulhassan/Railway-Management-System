<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Booking Confirm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/booking_confirm.css') }}" />
</head>

<body>
    <div class="container">
        <div class="confirm-card">
            <h3 class="text-center mb-4">Booking Confirmation</h3>

            <!-- Display Errors -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Train Details -->
            <div>
                <h5 class="section-title">Train Details</h5>
                <div class="info-row">
                    <div class="info-label">Train Name:</div>
                    <div>{{ $bookingData['schedule']->train->train_name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Departure:</div>
                    <div>{{ $bookingData['schedule']->sourceStation->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Arrival:</div>
                    <div>{{ $bookingData['schedule']->destinationStation->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Class:</div>
                    <div>{{ ucfirst($bookingData['class']) }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Date:</div>
                    <div>{{ $bookingData['schedule']->departure_time->format('Y-m-d') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Time:</div>
                    <div>{{ $bookingData['schedule']->departure_time->format('H:i A') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Number of Seats:</div>
                    <div>{{ count($bookingData['selected_seats']) }}</div>
                </div>
            </div>

            <!-- Food Info -->
            @if(!empty($bookingData['food_orders']))
                <div class="mt-30">
                    <h5 class="section-title">Food Order</h5>
                    <ul class="list-disc-pl-20">
                        @foreach($bookingData['food_orders'] as $foodOrder)
                            @php
                                $foodItem = App\Models\FoodItem::find($foodOrder['food_id']);
                            @endphp
                            @if($foodItem)
                                <li>{{ $foodItem->name }} x{{ $foodOrder['quantity'] }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Payment & Total -->
            <div class="mt-30">
                <h5 class="section-title">Payment</h5>
                @php
                    $seatPrice = 350; // Default price
                    $ticketPrice = App\Models\TicketPrice::where('train_id', $bookingData['schedule']->train_id)
                                                        ->where('compartment_id', $bookingData['compartment_id'])
                                                        ->first();
                    if ($ticketPrice) {
                        $seatPrice = $ticketPrice->base_price;
                    }
                    
                    $totalSeatPrice = $seatPrice * count($bookingData['selected_seats']);
                    $totalFoodPrice = 0;
                    
                    if (!empty($bookingData['food_orders'])) {
                        foreach ($bookingData['food_orders'] as $foodOrder) {
                            $foodItem = App\Models\FoodItem::find($foodOrder['food_id']);
                            if ($foodItem) {
                                $totalFoodPrice += $foodItem->price * $foodOrder['quantity'];
                            }
                        }
                    }
                    
                    $totalAmount = $totalSeatPrice + $totalFoodPrice;
                @endphp
                <div class="info-row">
                    <div class="info-label">Total Payout:</div>
                    <div class="fw-bold text-success">৳{{ $totalAmount }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('payment.initiate') }}" id="paymentForm">
                @csrf
                <button type="submit" class="btn btn-success mt-4 w-100 fw-bold py-3" id="paymentBtn">
                    <span class="btn-text">
                        <i class="bi bi-credit-card"></i> Proceed to Payment (SSLCommerz)
                    </span>
                    <span class="btn-loading d-none">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Processing...
                    </span>
                </button>
            </form>
            
            <div class="text-center mt-3">
                <small class="text-muted">Secure payment powered by SSLCommerz</small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('paymentBtn');
            const btnText = btn.querySelector('.btn-text');
            const btnLoading = btn.querySelector('.btn-loading');
            
            // Disable button and show loading
            btn.disabled = true;
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
        });
    </script>
</body>

</html>