<div class="dropdown ms-lg-3">
    @auth
        <button class="btn dropdown-toggle fw-bold" id="userDropdown" data-bs-toggle="dropdown">
            {{ Auth::user()->name }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item fw-bold" href="{{ route('user.profile') }}">Profile</a></li>
            <li><a class="dropdown-item fw-bold" href="{{ route('user.dashboard') }}">Dashboard</a></li>
            @if(Auth::user()->role === 'admin')
                <li><a class="dropdown-item fw-bold" href="{{ route('adminpanel') }}">Admin Panel</a></li>
            @endif
            <li>
                <hr class="dropdown-divider" />
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="dropdown-item fw-bold text-danger" style="border: none; background: none;">Logout</button>
                </form>
            </li>
        </ul>
    @else
        <div class="d-flex gap-2">
            <a href="{{ route('login.form') }}" class="btn btn-outline-primary rounded-pill fw-bold">Login</a>
            <a href="{{ route('register.form') }}" class="btn btn-primary rounded-pill fw-bold">Sign Up</a>
        </div>
    @endauth
</div>