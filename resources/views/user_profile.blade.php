<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
</head>

<body>
    <x-navbar />

    <div class="container">
        <div class="profile-card text-center">
            <h2>User Profile</h2>

            <!-- Success/Error Messages -->
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0 text-start">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Profile Avatar -->
            <img src="https://ui-avatars.com/api/?name=John+Doe&size=150&background=007bff&color=fff"
                alt="Profile Photo" class="profile-avatar mb-3 rounded-circle">

            <!-- Profile form -->
            <form action="" method="POST" enctype="multipart/form-data">
                <!-- Non-editable fields -->
                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" class="form-control" value="johndoe@example.com" readonly>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Role</label>
                    <input type="text" class="form-control" value="User" readonly>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">NID Number</label>
                    <input type="text" class="form-control" value="1234567890" readonly>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">NID Verification</label>
                    <input type="text" class="form-control" value="Verified" readonly>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Account Created</label>
                    <input type="text" class="form-control" value="2025-09-01 12:00" readonly>
                </div>

                <!-- Editable fields -->
                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Full Name</label>
                    <input type="text" class="form-control" name="name" value="John Doe" required>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Phone Number</label>
                    <input type="text" class="form-control" name="contact_number" value="+880123456789">
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Address</label>
                    <input type="text" class="form-control" name="address" value="Dhaka, Bangladesh">
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Date of Birth</label>
                    <input type="date" class="form-control" name="dob" value="2000-01-01" required>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Enter new password">
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Profile Photo</label>
                    <input type="file" class="form-control" name="photo" accept="image/*">
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Update Profile</button>
                </div>
            </form>
        </div>
    </div>

    <x-footer />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>