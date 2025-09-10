<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Booking Confirm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f9f9f9;
            font-weight: bold;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
        }

        .confirm-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: #007bff;
            margin-bottom: 15px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .info-label {
            color: #555;
        }

        .dashboard-link {
            display: inline-block;
            margin-top: 30px;
            font-weight: bold;
        }
    </style>
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
                <div style="margin-top: 30px;">
                    <h5 class="section-title">Food Order</h5>
                    <ul style="list-style-type: disc; padding-left: 20px;">
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
            <div style="margin-top: 30px;">
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