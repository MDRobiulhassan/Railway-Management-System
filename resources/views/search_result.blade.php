<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light px-4 py-2">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" width="40" />
        </a>

        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
            aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="#">Search</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="#">Schedule</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="#">Book Tickets</a>
                </li>
            </ul>
            <div class="dropdown ms-lg-3">
                <button class="btn dropdown-toggle fw-bold" id="userDropdown" data-bs-toggle="dropdown">
                    Robiul
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item fw-bold" href="#">Profile</a></li>
                    <li><a class="dropdown-item fw-bold" href="#">Dashboard</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item fw-bold text-danger" href="#">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h3 class="mb-4 text-center">Available Trains</h3>

        <div class="table-responsive">
            <table class="table table-bordered table-hover custom-table mx-auto">
                <thead class="table-light">
                    <tr>
                        <th>Train Name</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Seats Available</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Subarna Express</td>
                        <td>Dhaka</td>
                        <td>Chattogram</td>
                        <td>2025-08-06</td>
                        <td>07:00 AM</td>
                        <td>25</td>
                        <td>
                            <a href="{{ route('booking.step1') }}" class="btn book-btn">Book Now</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Silk City Express</td>
                        <td>Dhaka</td>
                        <td>Rajshahi</td>
                        <td>2025-08-06</td>
                        <td>08:30 AM</td>
                        <td>18</td>
                        <td>
                            <a href="{{ route('booking.step1') }}" class="btn book-btn">Book Now</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Sonar Bangla Express</td>
                        <td>Dhaka</td>
                        <td>Chattogram</td>
                        <td>2025-08-06</td>
                        <td>02:00 PM</td>
                        <td>32</td>
                        <td>
                            <a href="{{ route('booking.step1') }}" class="btn book-btn">Book Now</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>