<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</head>
<body>
<div class="container mt-5 p-4 border rounded shadow-sm" style="max-width: 600px;">
    <!-- Top section -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="/userprofile">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" width="50" height="50" class="rounded-circle">
            </a>
        </div>
        <div>
            <a href="">
                <span class="fw-bold">User Profile</span>
                {{-- <i class="bi bi-envelope ms-2"></i>  --}}
                <i class="bi bi-person-circle"></i>

            </a>
            

        </div>
    </div>

    <!-- Heading -->
    <h4 class="text-center mb-4">User Dashboard</h4>

    <!-- Upcoming Bookings -->
    <h5>Upcoming booking</h5>
    <div class="border p-2 mb-2 rounded">
        Route | Date | Paid | Status | etc.
    </div>
    <div class="border p-2 mb-4 rounded">
        Route | Date | Paid | Status | etc.
    </div>

    <!-- Past Bookings -->
    <h5>Past booking</h5>
    <div class="border p-2 mb-2 rounded">
        Route | Date | Paid | Status | etc.
    </div>
    <div class="border p-2 rounded">
        Route | Date | Paid | Status | etc.
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
