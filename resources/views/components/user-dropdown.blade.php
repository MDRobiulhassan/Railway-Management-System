<div class="dropdown ms-lg-3">
    <!-- Uncomment it once the authentication is done -->
    <!-- <button class="btn dropdown-toggle fw-bold" id="userDropdown" data-bs-toggle="dropdown">
        Robiul
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item fw-bold" href="#">Profile</a></li>
        <li><a class="dropdown-item fw-bold" href="#">Dashboard</a></li>
        <li>
            <hr class="dropdown-divider" />
        </li>
        <li><a class="dropdown-item fw-bold text-danger" href="#">Logout</a></li>
    </ul> -->

    <div class="d-flex gap-2">
        <a href="{{ route('login.form') }}" class="btn btn-outline-primary rounded-pill fw-bold">Login</a>
        <a href="{{ route('register.form') }}" class="btn btn-primary rounded-pill fw-bold">Sign Up</a>
    </div>
</div>