<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">
</head>

<body>
    <!-- Navbar -->
    <x-navbar />

    <div class="container">
        <h1 class="dashboard-heading text-center">User Dashboard</h1>

        <!-- Success/Error Messages -->
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Bookings -->
        <h3 class="section-heading">Your Bookings</h3>
        <hr>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Booking ID</th>
                        <th>Train</th>
                        <th>Compartment</th>
                        <th>Seats</th>
                        <th>Travel Date</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($bookings)
                    @forelse($bookings as $booking)
                        @php
                            $firstTicket = $booking->tickets->first();
                            $compartment = $firstTicket?->compartment;
                            $seatList = $booking->tickets->map(fn($t) => $t->seat?->seat_number)->filter()->implode(', ');
                            $travelDate = $firstTicket?->travel_date?->format('Y-m-d');
                        @endphp
                        <tr>
                            <td>{{ $booking->booking_id }}</td>
                            <td>{{ $booking->train->train_name }}</td>
                            <td>{{ $compartment?->compartment_name }} ({{ $compartment?->class_name }})</td>
                            <td>{{ $seatList }}</td>
                            <td>{{ $travelDate }}</td>
                            <td><span class="badge {{ $booking->status === 'confirmed' ? 'bg-success' : ($booking->status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">{{ ucfirst($booking->status) }}</span></td>
                            <td>{{ number_format($booking->total_amount, 2) }} BDT</td>
                            <td>
                                <a class="btn btn-info btn-sm" href="{{ route('ticket.view', $booking->booking_id) }}">View Ticket</a>
                                <a class="btn btn-primary btn-sm" href="{{ route('ticket.download', $booking->booking_id) }}">Download Ticket</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No bookings found.</td>
                        </tr>
                    @endforelse
                    @endisset
                </tbody>
            </table>
        </div>

    </div>

    <!-- Footer -->
    <x-footer />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>