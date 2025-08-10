<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Railway Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <style>

    </style>
</head>

<body>

    <!-- Navbar -->
    <x-navbar />

    <!-- Hero Section -->
    <section class="hero text-center">
        <div class="container">
            <h1 class="display-4">Explore Destinations with Speed and Comfort</h1>
            <p class="lead">Book your train journey easily and quickly with us.</p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 text-center">
        <div class="container">
            <h2 class="mb-4">Why Choose Us</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3 feature-icon">&#128646;</div>
                    <h4>Reliable Schedules</h4>
                    <p>Trains run on time, every time.</p>
                </div>
                <div class="col-md-4">
                    <div class="mb-3 feature-icon">&#128179;</div>
                    <h4>Affordable Tickets</h4>
                    <p>Prices that suit your budget.</p>
                </div>
                <div class="col-md-4">
                    <div class="mb-3 feature-icon">&#9989;</div>
                    <h4>Easy Booking</h4>
                    <p>Quick and simple online booking.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Train Schedule Preview -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Popular Trains</h2>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Train</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Departure</th>
                            <th>Arrival</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Express 101</td>
                            <td>Dhaka</td>
                            <td>Chittagong</td>
                            <td>08:00</td>
                            <td>12:30</td>
                            <td><span class="badge bg-success">On Time</span></td>
                        </tr>
                        <tr>
                            <td>Silk City</td>
                            <td>Dhaka</td>
                            <td>Rajshahi</td>
                            <td>09:00</td>
                            <td>13:45</td>
                            <td><span class="badge bg-warning text-dark">Delayed</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">What Our Users Say</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="testimonial">
                        <p>"The booking experience was seamless and the train was on time. Highly recommended!"</p>
                        <strong>- Mahin R.</strong>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="testimonial">
                        <p>"Easy to use and very efficient. I travel frequently and this system never fails."</p>
                        <strong>- Nafisa S.</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-footer />

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>