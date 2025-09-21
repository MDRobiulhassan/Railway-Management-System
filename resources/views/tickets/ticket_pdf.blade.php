<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Train Ticket - Booking {{ $booking->booking_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .ticket-container {
            border: 2px solid #2c3e50;
            border-radius: 6px;
            overflow: hidden;
        }

        .ticket-header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .ticket-header h1 {
            margin: 0;
            font-size: 20px;
        }

        .ticket-header h3 {
            margin: 5px 0 0;
            font-size: 14px;
            font-weight: normal;
        }

        .ticket-body {
            padding: 20px;
            background: #fff;
        }

        .section {
            margin-bottom: 15px;
            padding: 12px;
            border-left: 3px solid #2c3e50;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .section h4 {
            margin: 0 0 10px;
            font-size: 13px;
            text-transform: uppercase;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .row {
            margin-bottom: 8px;
        }

        .col {
            display: inline-block;
            width: 48%;
            vertical-align: top;
        }

        .route-info {
            text-align: center;
            margin: 15px 0;
        }

        .airport {
            display: inline-block;
            width: 40%;
            vertical-align: top;
        }

        .flight-arrow {
            display: inline-block;
            width: 15%;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            color: #2c3e50;
        }

        .ticket-details table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background: #f1f1f1;
            font-size: 10px;
            text-transform: uppercase;
        }

        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 10px;
            color: white;
        }

        .badge-success {
            background: #28a745;
        }

        .badge-warning {
            background: #f39c12;
        }

        .badge-secondary {
            background: #6c757d;
        }

        .badge-danger {
            background: #e74c3c;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
            color: #555;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        /* Page Break */
        .page-break {
            page-break-before: always;
        }

        .second-page-header {
            background: #2c3e50;
            color: white;
            padding: 12px;
            text-align: center;
            margin-bottom: 20px;
        }

        .second-page-header h2 {
            margin: 0;
            font-size: 16px;
        }

        .important-info {
            background: #fff8e1;
            border: 1px solid #f39c12;
            padding: 12px;
            font-size: 11px;
        }

        .important-info h4 {
            margin-top: 0;
            font-size: 12px;
        }

        .important-info ul {
            margin: 5px 0 0 18px;
            padding: 0;
        }

        .important-info li {
            margin-bottom: 6px;
        }

        .contact-section {
            margin-top: 15px;
            padding: 12px;
            border: 1px solid #ccc;
            font-size: 11px;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- First Page -->
    <div class="ticket-container">
        <div class="ticket-header">
            <h1>Bangladesh Railways</h1>
            <h3>Train Ticket</h3>
            <p>Booking ID: {{ $booking->booking_id }}</p>
        </div>

        <div class="ticket-body">
            <!-- Train Info -->
            <div class="section">
                <h4>Train Details</h4>
                <div class="row">
                    <div class="col">
                        Train: {{ $booking->train->train_name }}<br>
                        Type: {{ $booking->train->train_type }}
                    </div>
                    <div class="col">
                        Status:
                        <span
                            class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($booking->status) }}
                        </span><br>
                        Booked: {{ $booking->booking_date->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>

            <!-- Route -->
            <div class="section">
                <h4>Route Information</h4>
                <div class="route-info">
                    <div class="airport">
                        <strong>From: {{ $schedule?->sourceStation?->name ?? '—' }}</strong><br>
                        Departure: {{ $schedule?->departure_time?->format('Y-m-d H:i') ?? ($booking->tickets->first()?->travel_date?->format('Y-m-d') ?? '—') }}
                    </div>
                    <div class="flight-arrow">---></div>
                    <div class="airport">
                        <strong>To: {{ $schedule?->destinationStation?->name ?? '—' }}</strong><br>
                        Arrival: {{ $schedule?->arrival_time?->format('Y-m-d H:i') ?? '—' }}
                    </div>
                </div>
            </div>

            <!-- Passenger -->
            <div class="section">
                <h4>Passenger Info</h4>
                <div class="row">
                    <div class="col">
                        Name: {{ $booking->user->name }}<br>
                        Email: {{ $booking->user->email }}<br>
                        Phone: {{ $booking->user->contact_number }}
                    </div>
                    <div class="col">
                        NID: {{ $booking->user->nid_number }}<br>
                        DOB: {{ optional($booking->user->dob)->format('Y-m-d') }}<br>
                        Paid: {{ number_format($booking->total_amount, 2) }} BDT
                    </div>
                </div>
            </div>

            <!-- Tickets -->
            <div class="section">
                <h4>Ticket(s)</h4>
                @foreach($booking->tickets as $ticket)
                    <div class="ticket-details">
                        <table>
                            <tr>
                                <th>Ticket ID</th>
                                <th>Seat</th>
                                <th>Compartment</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                            <tr>
                                <td>{{ $ticket->ticket_id }}</td>
                                <td>{{ $ticket->seat->seat_number }}</td>
                                <td>{{ $ticket->compartment?->compartment_name }} ({{ $ticket->compartment?->class_name }})</td>
                                <td>{{ $ticket->travel_date->format('Y-m-d') }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $ticket->ticket_status === 'active' ? 'success' : ($ticket->ticket_status === 'used' ? 'secondary' : 'danger') }}">
                                        {{ ucfirst($ticket->ticket_status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                @endforeach
            </div>

            <!-- Food Orders -->
            @if($booking->foodOrders->count() > 0)
                <div class="section">
                    <h4>Food Orders</h4>
                    <div class="ticket-details">
                        <table>
                            <tr>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                            @foreach($booking->foodOrders as $foodOrder)
                                <tr>
                                    <td>{{ $foodOrder->foodItem->name }}</td>
                                    <td>{{ $foodOrder->foodItem->category }}</td>
                                    <td>{{ $foodOrder->quantity }}</td>
                                    <td>{{ number_format($foodOrder->foodItem->price, 2) }} BDT</td>
                                    <td>{{ number_format($foodOrder->foodItem->price * $foodOrder->quantity, 2) }} BDT</td>
                                </tr>
                            @endforeach
                        </table>
                        <div style="margin-top: 10px; text-align: right; font-weight: bold;">
                            Food Total: {{ number_format($booking->foodOrders->sum(function($order) { return $order->foodItem->price * $order->quantity; }), 2) }} BDT
                        </div>
                    </div>
                </div>
            @endif

            <!-- Payment Information -->
            @if($booking->payment)
                <div class="section">
                    <h4>Payment Details</h4>
                    <div class="row">
                        <div class="col">
                            Payment Method: {{ $booking->payment->payment_method }}<br>
                            Transaction ID: {{ $booking->payment->transaction_id ?? 'N/A' }}
                        </div>
                        <div class="col">
                            Payment Status: 
                            <span class="badge badge-{{ $booking->payment->payment_status === 'completed' ? 'success' : ($booking->payment->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($booking->payment->payment_status) }}
                            </span><br>
                            Paid At: {{ $booking->payment->paid_at ? $booking->payment->paid_at->format('Y-m-d H:i') : 'N/A' }}
                        </div>
                    </div>
                </div>
            @endif

            <div class="footer">
                Thank you for traveling with Bangladesh Railways<br>
                Generated on: {{ now()->format('Y-m-d H:i:s') }}
            </div>
        </div>
    </div>

    <!-- Second Page -->
    <div class="page-break">
        <div class="second-page-header">
            <h2>Important Travel Information</h2>
            <p>Booking: {{ $booking->booking_id }} | Train: {{ $booking->train->train_name }}</p>
        </div>

        <div style="padding: 15px;">
            <div class="important-info">
                <h4>Travel Guidelines</h4>
                <p><strong>Bangladesh Railway Travel Guidelines:</strong></p>
                <ol style="margin-left: 18px;">
                    <li>Buy tickets only from official sources (online/app/counters).</li>
                    <li>Max 4 tickets per person per transaction (ID required).</li>
                    <li>Carry the same ID used for booking during travel.</li>
                    <li>Children 3–12 need minor tickets; below 3 years free.</li>
                    <li>Arrive at station 30–40 mins before departure.</li>
                    <li>Sit in your assigned seat only; show ticket + ID when asked.</li>
                    <li>No roof/buffer riding, smoking, or traveling without a ticket.</li>
                    <li>Keep compartments clean; secure your luggage.</li>
                    <li>No platform entry without a valid ticket during rush times.</li>
                    <li>Trains may be delayed in fog/rain—follow announcements.</li>
                </ol>
            </div>

            <div class="contact-section">
                Emergency: +880-1636-114935<br>
                Website: https://railapp.railway.gov.bd/<br>
                Email: support@eticket.railway.gov.bd<br>
                <small>This document is computer generated and does not require a signature.<br>
                    Generated: {{ now()->format('Y-m-d H:i:s') }}</small>
            </div>
        </div>
    </div>
</body>

</html>