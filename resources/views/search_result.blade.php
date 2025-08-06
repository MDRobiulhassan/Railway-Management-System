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

    <x-navbar />


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