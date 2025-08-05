<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-5 p-4 border rounded shadow-sm" style="max-width: 600px;">
    <!-- Top Section -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" width="50" height="50" class="rounded-circle">
        <div>
            <span class="fw-bold">User name</span>
            <i class="bi bi-envelope ms-2"></i>
        </div>
    </div>

    <h4 class="text-center mb-4">User Profile</h4>

    <!-- Profile Form -->

    {{-- at action {{ route('userprofile.update') }} --}}
    <form method="POST" action="">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" value="John Doe" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="john@example.com" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="017xxxxxxxx" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">NID</label>
            <input type="text" name="nid" value="1234567890" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" placeholder="Change Password" class="form-control">
        </div>

        <!-- Add more fields if needed -->
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
</body>
</html>
