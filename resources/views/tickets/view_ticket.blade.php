<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rail Ticket - Booking {{ $booking->booking_id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .ticket-container {
            max-width: 800px;
            margin: 20px auto;
            border: 2px solid #2c3e50;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .ticket-header {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .ticket-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }

        .ticket-header h3 {
            margin: 10px 0 0;
            font-size: 18px;
            font-weight: normal;
            opacity: 0.9;
        }

        .ticket-body {
            padding: 30px;
            background: #fff;
        }

        .section {
            margin-bottom: 25px;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background: #fafafa;
        }

        .section h4 {
            margin: 0 0 15px;
            font-size: 18px;
            text-transform: uppercase;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 8px;
        }

        .route-info {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background: linear-gradient(135deg, #ecf0f1, #bdc3c7);
            border-radius: 10px;
        }

        .airport {
            display: inline-block;
            width: 40%;
            vertical-align: top;
            padding: 15px;
        }

        .flight-arrow {
            display: inline-block;
            width: 15%;
            text-align: center;
            font-weight: bold;
            font-size: 24px;
            color: #2c3e50;
        }

        .ticket-details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .ticket-details th,
        .ticket-details td {
            border: 1px solid #ddd;
            padding: 12px 8px;
            text-align: left;
        }

        .ticket-details th {
            background: #2c3e50;
            color: white;
            font-weight: bold;
        }

        .ticket-details tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }

        .badge-success {
            background: #27ae60;
        }

        .badge-warning {
            background: #f39c12;
        }

        .badge-secondary {
            background: #95a5a6;
        }

        .badge-danger {
            background: #e74c3c;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            background: #ecf0f1;
            border-top: 1px solid #ddd;
            font-size: 14px;
            color: #555;
        }

        .action-buttons {
            text-align: center;
            margin: 20px 0;
        }

        .action-buttons .btn {
            margin: 0 10px;
            padding: 10px 20px;
        }

        .food-total {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 5px;
            text-align: right;
            font-weight: bold;
            color: #27ae60;
            margin-top: 15px;
        }

        @media (max-width: 768px) {
            .ticket-container {
                margin: 10px;
                border-radius: 5px;
            }

            .ticket-header {
                padding: 20px;
            }

            .ticket-header h1 {
                font-size: 24px;
            }

            .ticket-body {
                padding: 20px;
            }

            .section {
                padding: 15px;
            }

            .airport {
                width: 100%;
                margin-bottom: 15px;
            }

            .flight-arrow {
                width: 100%;
                margin: 10px 0;
            }

            .action-buttons .btn {
                display: block;
                margin: 10px 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <x-navbar />

    <div class="container">
        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <a href="{{ route('ticket.download', $booking->booking_id) }}" class="btn btn-primary">
                <i class="fas fa-download"></i> Download PDF
            </a>
        </div>

        <!-- Ticket Container -->
        <div class="ticket-container">
            <div class="ticket-header">
                <h1><i class="fas fa-train"></i> Bangladesh Railways</h1>
                <h3>Train Ticket</h3>
                <p><strong>Booking ID:</strong> {{ $booking->booking_id }}</p>
            </div>

            <div class="ticket-body">
                <!-- Train Info -->
                <div class="section">
                    <h4><i class="fas fa-info-circle"></i> Train Details</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Train:</strong> {{ $booking->train->train_name }}<br>
                            <strong>Type:</strong> {{ $booking->train->train_type }}<br>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <span class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($booking->status) }}
                            </span><br>
                            <strong>Booked:</strong> {{ $booking->booking_date->format('Y-m-d H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Route -->
                <div class="section">
                    <h4><i class="fas fa-route"></i> Route Information</h4>
                    <div class="route-info">
                        <div class="airport">
                            <h5><strong>{{ $booking->tickets->first()?->compartment?->class_name }}</strong></h5>
                            <p><strong>From:</strong> {{ $schedule?->sourceStation?->name ?? '—' }}<br>
                            <strong>Departure:</strong> {{ $schedule?->departure_time?->format('Y-m-d H:i') ?? ($booking->tickets->first()?->travel_date?->format('Y-m-d') ?? '—') }}</p>
                        </div>
                        <div class="flight-arrow">
                            <i class="fas fa-train"></i>
                        </div>
                        <div class="airport">
                            <h5><strong>&nbsp;</strong></h5>
                            <p><strong>To:</strong> {{ $schedule?->destinationStation?->name ?? '—' }}<br>
                            <strong>Arrival:</strong> {{ $schedule?->arrival_time?->format('Y-m-d H:i') ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Passenger -->
                <div class="section">
                    <h4><i class="fas fa-user"></i> Passenger Info</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Name:</strong> {{ $booking->user->name }}<br>
                            <strong>Email:</strong> {{ $booking->user->email }}<br>
                            <strong>Phone:</strong> {{ $booking->user->contact_number }}
                        </div>
                        <div class="col-md-6">
                            <strong>NID:</strong> {{ $booking->user->nid_number }}<br>
                            <strong>DOB:</strong> {{ optional($booking->user->dob)->format('Y-m-d') }}<br>
                            <strong>Total Paid:</strong> {{ number_format($booking->total_amount, 2) }} BDT
                        </div>
                    </div>
                </div>

                <!-- Tickets -->
                <div class="section">
                    <h4><i class="fas fa-ticket-alt"></i> Ticket(s)</h4>
                    @foreach($booking->tickets as $ticket)
                        <div class="ticket-details">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Ticket ID</th>
                                        <th>Seat</th>
                                        <th>Compartment</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $ticket->ticket_id }}</td>
                                        <td>{{ $ticket->seat->seat_number }}</td>
                                        <td>{{ $ticket->compartment?->compartment_name }} ({{ $ticket->compartment?->class_name }})</td>
                                        <td>{{ $ticket->travel_date->format('Y-m-d') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $ticket->ticket_status === 'active' ? 'success' : ($ticket->ticket_status === 'used' ? 'secondary' : 'danger') }}">
                                                {{ ucfirst($ticket->ticket_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>

                <!-- Food Orders -->
                @if($booking->foodOrders->count() > 0)
                    <div class="section">
                        <h4><i class="fas fa-utensils"></i> Food Orders</h4>
                        <div class="ticket-details">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($booking->foodOrders as $foodOrder)
                                        <tr>
                                            <td>{{ $foodOrder->foodItem->name }}</td>
                                            <td>{{ $foodOrder->foodItem->category }}</td>
                                            <td>{{ $foodOrder->quantity }}</td>
                                            <td>{{ number_format($foodOrder->foodItem->price, 2) }} BDT</td>
                                            <td>{{ number_format($foodOrder->foodItem->price * $foodOrder->quantity, 2) }} BDT</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="food-total">
                                Food Total: {{ number_format($booking->foodOrders->sum(function($order) { return $order->foodItem->price * $order->quantity; }), 2) }} BDT
                            </div>
                        </div>
                    </div>
                @endif

                <div class="footer">
                    <p><i class="fas fa-heart"></i> Thank you for traveling with Bangladesh Railways</p>
                    <small>Generated on: {{ now()->format('Y-m-d H:i:s') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <x-footer />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
