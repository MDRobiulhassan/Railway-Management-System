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

    <div class="container mt-5">
        <h2 class="mb-4 text-center fw-bold">Book Your Tickets</h2>
        <div class="search-card mx-auto" style="max-width: 900px;">
            <form action="{{ route('search.result') }}" method="GET">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="from" class="form-label fw-bold">From</label>
                        <select id="from" class="form-select fw-bold">
                            <option disabled selected>Departure City</option>
                            <option>Dhaka</option>
                            <option>Chattogram</option>
                            <option>Rajshahi</option>
                            <option>Khulna</option>
                            <option>Barisal</option>
                            <option>Sylhet</option>
                            <option>Rangpur</option>
                            <option>Mymensingh</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="to" class="form-label fw-bold">To</label>
                        <select id="to" class="form-select fw-bold">
                            <option disabled selected>Destination City</option>
                            <option>Dhaka</option>
                            <option>Chattogram</option>
                            <option>Rajshahi</option>
                            <option>Khulna</option>
                            <option>Barisal</option>
                            <option>Sylhet</option>
                            <option>Rangpur</option>
                            <option>Mymensingh</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="class" class="form-label fw-bold">Class</label>
                        <select id="class" class="form-select fw-bold">
                            <option selected disabled>Choose</option>
                            <option value="economy">AC</option>
                            <option value="business">Shovan</option>
                            <option value="first">Snigdha</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="date" class="form-label fw-bold">Date</label>
                        <input type="date" id="date" class="form-control fw-bold" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary px-5 fw-bold">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>