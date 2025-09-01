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
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Profile updated successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Error updating profile.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <!-- Upcoming Bookings -->
        <h3 class="section-heading">Upcoming Bookings</h3>
        <hr>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Booking ID</th>
                        <th>Train</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>BK1001</td>
                        <td>Rajdhani Express</td>
                        <td>Dhaka</td>
                        <td>Chittagong</td>
                        <td>2025-09-10 08:00</td>
                        <td>2025-09-10 14:00</td>
                        <td><span class="badge bg-success">Confirmed</span></td>
                        <td>
                            <button class="btn btn-info btn-sm">View Ticket</button>
                            <button class="btn btn-primary btn-sm">Download Ticket</button>
                            <button class="btn btn-danger btn-sm">Cancel Ticket</button>
                            <button class="btn btn-warning btn-sm">Reschedule</button>
                        </td>
                    </tr>
                    <tr>
                        <td>BK1002</td>
                        <td>Sundarban Express</td>
                        <td>Dhaka</td>
                        <td>Khulna</td>
                        <td>2025-09-15 09:00</td>
                        <td>2025-09-15 16:00</td>
                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                        <td>
                            <button class="btn btn-info btn-sm">View Ticket</button>
                            <button class="btn btn-primary btn-sm">Download Ticket</button>
                            <button class="btn btn-danger btn-sm">Cancel Ticket</button>
                            <button class="btn btn-warning btn-sm">Reschedule</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Past Bookings -->
        <h3 class="section-heading">Past Bookings</h3>
        <hr>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Booking ID</th>
                        <th>Train</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>BK0999</td>
                        <td>Suborno Express</td>
                        <td>Dhaka</td>
                        <td>Sylhet</td>
                        <td>2025-08-25 07:00</td>
                        <td>2025-08-25 12:00</td>
                        <td><span class="badge bg-secondary">Completed</span></td>
                        <td>
                            <button class="btn btn-info btn-sm">View Ticket</button>
                            <button class="btn btn-primary btn-sm">Download Ticket</button>
                        </td>
                    </tr>
                    <tr>
                        <td>BK0998</td>
                        <td>Chattogram Express</td>
                        <td>Dhaka</td>
                        <td>Chittagong</td>
                        <td>2025-08-20 06:30</td>
                        <td>2025-08-20 13:00</td>
                        <td><span class="badge bg-danger">Cancelled</span></td>
                        <td>
                            <button class="btn btn-info btn-sm">View Ticket</button>
                            <button class="btn btn-primary btn-sm">Download Ticket</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <x-footer />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>