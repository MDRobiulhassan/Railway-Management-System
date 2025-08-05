<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bangladesh Railway</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Custom CSS (optional) -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('logo.png') }}" alt="Logo" style="height: 40px;">
        </a>

        <!-- Mobile toggle button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse justify-content-between" id="mainNavbar">

            <!-- Center links -->
            <ul class="navbar-nav mx-auto text-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('schedule') }}">Schedule</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('train.search') }}">Search Train</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/book-ticket') }}">Book Ticket</a>
                </li>
            </ul>

            <!-- Right side: User dropdown or Login button -->
             <div class="dropdown text-center mt-2 mt-lg-0">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    John Doe
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown" style="min-width: 200px;">
                    <a class="dropdown-item" href="#">Profile</a>
                    <a class="dropdown-item" href="#">Dashboard</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="#">Sign Out</a>
                </div>

        </div>
    </div>
</nav>

<!-- Main content area -->
<main class="py-4">
    <div class="container">
        @yield('content')
    </div>
</main>

<!-- Bootstrap JS + Dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" crossorigin="anonymous"></script>

</body>
</html>
