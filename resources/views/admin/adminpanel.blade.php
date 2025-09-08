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
    <x-footer />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>