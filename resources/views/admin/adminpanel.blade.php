<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scoped Search CSS only for navbar -->
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/adminpanel.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <!-- Navbar scoped wrapper -->
    <div class="navbar-wrapper">
        <x-navbar />
    </div>

    <div class="container pb-3">
        <h1>Admin Panel Dashboard</h1>

        @isset($stats)
        <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
            <div class="col">
                <div class="card text-center p-3 shadow-sm border-0 rounded-3">
                    <i class="fa-solid fa-users text-primary mb-2" style="font-size: 1.5rem;"></i>
                    <h6 class="mb-1">Users</h6>
                    <div class="display-6">{{ number_format($stats['users'] ?? 0) }}</div>
                    <small class="text-muted">Admins: {{ number_format($stats['admins'] ?? 0) }}</small>
                </div>
            </div>
            <div class="col">
                <div class="card text-center p-3 shadow-sm border-0 rounded-3">
                    <i class="fa-solid fa-train text-success mb-2" style="font-size: 1.5rem;"></i>
                    <h6 class="mb-1">Trains</h6>
                    <div class="display-6">{{ number_format($stats['trains'] ?? 0) }}</div>
                    <small class="text-muted">Schedules: {{ number_format($stats['schedules'] ?? 0) }}</small>
                </div>
            </div>
            <div class="col">
                <div class="card text-center p-3 shadow-sm border-0 rounded-3">
                    <i class="fa-solid fa-building text-info mb-2" style="font-size: 1.5rem;"></i>
                    <h6 class="mb-1">Stations</h6>
                    <div class="display-6">{{ number_format($stats['stations'] ?? 0) }}</div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card p-3 h-100">
                    <h6 class="mb-3">Bookings</h6>
                    <div class="d-flex justify-content-between">
                        <span>Total</span>
                        <strong>{{ number_format($stats['bookings'] ?? 0) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Confirmed</span>
                        <strong class="text-success">{{ number_format($stats['bookings_confirmed'] ?? 0) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Pending</span>
                        <strong class="text-warning">{{ number_format($stats['bookings_pending'] ?? 0) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Cancelled</span>
                        <strong class="text-danger">{{ number_format($stats['bookings_cancelled'] ?? 0) }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 h-100">
                    <h6 class="mb-3">Payments</h6>
                    <div class="d-flex justify-content-between">
                        <span>Revenue</span>
                        <strong>৳ {{ number_format($stats['revenue'] ?? 0, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Completed</span>
                        <strong class="text-success">{{ number_format($stats['payments_completed'] ?? 0) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Pending</span>
                        <strong class="text-warning">{{ number_format($stats['payments_pending'] ?? 0) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Failed</span>
                        <strong class="text-danger">{{ number_format($stats['payments_failed'] ?? 0) }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 h-100">
                    <h6 class="mb-3">Catering</h6>
                    <div class="d-flex justify-content-between">
                        <span>Food Items</span>
                        <strong>{{ number_format($stats['food_items'] ?? 0) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Food Orders</span>
                        <strong>{{ number_format($stats['food_orders'] ?? 0) }}</strong>
                    </div>
                </div>
            </div>
        </div>
        @endisset

        <div class="row g-4">

            <!-- Passengers -->
            <div class="col-md-3">
                <a href="{{ route('admin.users') }}" class="text-decoration-none">
                    <div class="card text-center p-3 hover-effect">
                        <i class="fa-solid fa-users"></i>
                        <h5 class="card-title">Users</h5>
                        <p>Manage passengers & admins</p>
                    </div>
                </a>
            </div>

            <!-- Trains -->
            <div class="col-md-3">
                <a href="{{ route('admin.trains') }}" class="text-decoration-none">
                    <div class="card text-center p-3 hover-effect">
                        <i class="fa-solid fa-train"></i>
                        <h5 class="card-title">Trains</h5>
                        <p>Manage trains</p>
                    </div>
                </a>
            </div>

            <!-- Stations -->
            <div class="col-md-3">
                <a href="{{ route('admin.stations') }}" class="text-decoration-none">
                    <div class="card text-center p-3 hover-effect">
                        <i class="fa-solid fa-building"></i>
                        <h5 class="card-title">Stations</h5>
                        <p>Manage railway stations</p>
                    </div>
                </a>
            </div>

            <!-- Routes -->
            <div class="col-md-3">
                <a href="{{ route('admin.schedule') }}" class="text-decoration-none">
                    <div class="card text-center p-3 hover-effect">
                        <i class="fa-solid fa-route"></i>
                        <h5 class="card-title">Schedules</h5>
                        <p>Define train schedules</p>
                    </div>
                </a>
            </div>

            <!-- Coaches -->
            <div class="col-md-3">
                <a href="{{ route('admin.compartments') }}" class="text-decoration-none">
                    <div class="card text-center p-3 hover-effect">
                        <i class="fa-solid fa-couch"></i>
                        <h5 class="card-title">Compartments</h5>
                        <p>Manage train compartments</p>
                    </div>
                </a>
            </div>

            <!-- Seats -->
            <div class="col-md-3">
                <a href="{{ route('admin.seats') }}" class="text-decoration-none">
                    <div class="card text-center p-3 hover-effect">
                        <i class="fa-solid fa-chair"></i>
                        <h5 class="card-title">Seats</h5>
                        <p>Manage seats in compartments</p>
                    </div>
                </a>
            </div>

            <!-- Tickets -->
            <div class="col-md-3">
                <a href="{{ route('admin.tickets') }}" class="text-decoration-none">
                    <div class="card text-center p-3 hover-effect">
                        <i class="fa-solid fa-ticket"></i>
                        <h5 class="card-title">Tickets</h5>
                        <p>Manage booked tickets</p>
                    </div>
                </a>
            </div>

            <!-- Tickets Prices-->
            <div class="col-md-3">
                <a href="{{ route('admin.ticket_prices') }}" class="text-decoration-none">
                    <div class="card text-center p-3 hover-effect">
                        <i class="fa-solid fa-ticket"></i>
                        <h5 class="card-title">Ticket Pricing</h5>
                        <p>Manage ticket pricing</p>
                    </div>
                </a>
            </div>

            <!-- Bookings -->
            <div class="col-md-3">
                <a href="{{ route('admin.bookings') }}" class="text-decoration-none">
                    <div class="card text-center p-3 hover-effect">
                        <i class="fa-solid fa-receipt"></i>
                        <h5 class="card-title">Bookings</h5>
                        <p>View booking history</p>
                    </div>
                </a>
            </div>

            <!-- Payments -->
            <div class="col-md-3">
                <a href="{{ route('admin.payments') }}" class="text-decoration-none">
                    <div class="card text-center p-3 hover-effect">
                        <i class="fa-solid fa-credit-card"></i>
                        <h5 class="card-title">Payments</h5>
                        <p>Monitor transactions & status</p>
                    </div>
                </a>
            </div>

            <!-- NID Verification -->
            <div class="col-md-3">
                <a href="{{ route('admin.nid') }}" class="text-decoration-none">
                    <div class="card text-center p-3 hover-effect">
                        <i class="fa-solid fa-id-card"></i>
                        <h5 class="card-title">Govt NID Database</h5>
                        <p>NID Database</p>
                    </div>
                </a>
            </div>

            <!-- Catering Items -->
            <div class="col-md-3">
                <a href="{{ route('admin.food_items') }}" class="text-decoration-none">
                    <div class="card text-center p-3 hover-effect">
                        <i class="fa-solid fa-utensils"></i>
                        <h5 class="card-title">Food Items</h5>
                        <p>Manage food items</p>
                    </div>
                </a>
            </div>

            <!-- Catering Orders -->
            <div class="col-md-3">
                <a href="{{ route('admin.food_order') }}" class="text-decoration-none">
                    <div class="card text-center p-3 hover-effect">
                        <i class="fa-solid fa-burger"></i>
                        <h5 class="card-title">Food Orders</h5>
                        <p>Track food orders</p>
                    </div>
                </a>
            </div>

        </div>
    </div>
    @isset($recentBookings)
    <div class="container pb-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card p-3 h-100">
                    <h5 class="mb-3">Recent Bookings</h5>
                    <ul class="list-group list-group-flush">
                        @forelse($recentBookings as $booking)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    #{{ $booking->booking_id }} — {{ $booking->user->name ?? 'User' }}
                                    <small class="text-muted">({{ $booking->train->train_name ?? 'Train' }})</small>
                                </span>
                                <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                            </li>
                        @empty
                            <li class="list-group-item">No recent bookings</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3 h-100">
                    <h5 class="mb-3">Recent Payments</h5>
                    <ul class="list-group list-group-flush">
                        @forelse($recentPayments as $payment)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    #{{ $payment->payment_id }} — ৳ {{ number_format($payment->amount, 2) }}
                                    <small class="text-muted">(Booking #{{ $payment->booking_id }})</small>
                                </span>
                                <span class="badge {{ $payment->payment_status === 'completed' ? 'bg-success' : ($payment->payment_status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($payment->payment_status) }}
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item">No recent payments</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endisset

    <x-footer />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>