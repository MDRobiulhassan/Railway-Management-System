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
                    <div>৳{{ $totalAmount }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Payment Method:</div>
                    <div>{{ $bookingData['payment_method'] ?? 'N/A' }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('booking.finalize') }}">
                @csrf
                <button type="submit" class="btn btn-primary mt-4 w-100 fw-bold">Proceed</button>
            </form>
        </div>
    </div>

</body>

</html>