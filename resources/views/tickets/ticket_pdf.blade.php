<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Flight Ticket - {{ $booking->flight->flight_number }}</title>
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
            <h1>Bangladesh Airlines</h1>
            <h3>Flight Ticket</h3>
            <p>Booking ID: {{ $booking->booking_id }}</p>
        </div>

        <div class="ticket-body">
            <!-- Flight Info -->
            <div class="section">
                <h4>Flight Details</h4>
                <div class="row">
                    <div class="col">
                        Flight No: {{ $booking->flight->flight_number }}<br>
                        Aircraft: {{ $booking->flight->aircraft->model }}<br>
                        Gate: {{ $booking->flight->gate ?? 'TBA' }}<br>
                        Terminal: {{ $booking->flight->terminal ?? 'TBA' }}
                    </div>
                    <div class="col">
                        Duration: {{ $booking->flight->duration_minutes }} mins<br>
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
                        <strong>{{ $booking->flight->departureAirport->city }}</strong><br>
                        ({{ $booking->flight->departureAirport->code }})<br>
                        {{ $booking->flight->departureAirport->name }}<br>
                        {{ $booking->flight->departure_time->format('Y-m-d H:i') }}
                    </div>
                    <div class="flight-arrow">---></div>
                    <div class="airport">
                        <strong>{{ $booking->flight->arrivalAirport->city }}</strong><br>
                        ({{ $booking->flight->arrivalAirport->code }})<br>
                        {{ $booking->flight->arrivalAirport->name }}<br>
                        {{ $booking->flight->arrival_time->format('Y-m-d H:i') }}
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
                        DOB: {{ $booking->user->dob->format('Y-m-d') }}<br>
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
                                <th>Class</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                            <tr>
                                <td>{{ $ticket->ticket_id }}</td>
                                <td>{{ $ticket->seat->seat_number }}</td>
                                <td>{{ $ticket->seatClass->name ?? 'N/A' }}</td>
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

            <div class="footer">
                Thank you for flying with Bangladesh Airlines<br>
                Generated on: {{ now()->format('Y-m-d H:i:s') }}
            </div>
        </div>
    </div>

    <!-- Second Page -->
    <div class="page-break">
        <div class="second-page-header">
            <h2>Important Travel Information</h2>
            <p>Booking: {{ $booking->booking_id }} | Flight: {{ $booking->flight->flight_number }}</p>
        </div>

        <div style="padding: 15px;">
            <div class="important-info">
                <h4>Travel Guidelines</h4>
                <ul>
                    <li>Check-in at least 2h before international flights, 1h before domestic.</li>
                    <li>Valid government-issued photo ID required.</li>
                    <li>Baggage Allowance: Economy 20kg, Business 30kg, First 40kg.</li>
                    <li>Prohibited items include sharp objects, flammables, liquids >100ml.</li>
                    <li>Tickets are non-transferable and non-refundable.</li>
                    <li>Boarding starts 30 minutes before departure.</li>
                </ul>
            </div>

            <div class="contact-section">
                Emergency: +880-1636-114935<br>
                Website: www.bangladeshairlines.com<br>
                Email: support@bangladeshairlines.com<br>
                <small>This document is computer generated and does not require a signature.<br>
                    Generated: {{ now()->format('Y-m-d H:i:s') }}</small>
            </div>
        </div>
    </div>
</body>

</html>