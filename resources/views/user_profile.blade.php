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
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Please correct the highlighted errors.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Profile Avatar -->
            @if($user->photo && file_exists(public_path('storage/' . $user->photo)))
                <img src="{{ asset('storage/' . $user->photo) }}" alt="Profile Photo" class="profile-avatar mb-3 rounded-circle">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=150&background=007bff&color=fff" alt="Profile Photo" class="profile-avatar mb-3 rounded-circle">
            @endif

            <!-- Profile form -->
            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Non-editable fields -->
                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Role</label>
                    <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" readonly>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">NID Number</label>
                    <input type="text" class="form-control" value="{{ $user->nid_number }}" readonly>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">NID Verification</label>
                    <input type="text" class="form-control" value="{{ $user->nid_verified ? 'Verified' : 'Not Verified' }}" readonly>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Account Created</label>
                    <input type="text" class="form-control" value="{{ $user->created_at?->format('Y-m-d H:i') }}" readonly>
                </div>

                <!-- Editable fields -->
                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Full Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Phone Number</label>
                    <input type="text" class="form-control @error('contact_number') is-invalid @enderror" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}">
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Address</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address', $user->address) }}">
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Date of Birth</label>
                    <input type="date" class="form-control @error('dob') is-invalid @enderror" name="dob" value="{{ old('dob', optional($user->dob)->format('Y-m-d')) }}" required>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter new password">
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Profile Photo</label>
                    <input type="file" class="form-control @error('photo') is-invalid @enderror" name="photo" accept="image/*">
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