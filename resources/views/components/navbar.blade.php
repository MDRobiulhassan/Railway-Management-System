<nav class="navbar navbar-expand-lg navbar-light bg-light px-4 py-2">
    <a class="navbar-brand fw-bold" href="{{ route('welcome') }}">
        <span class="brand-red">Bangladesh</span>
        <span class="brand-green">Railway</span>
    </a>


    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
        aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('welcome') }}">Home</a></li>
            <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('search') }}">Search</a></li>
            <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('search.result') }}">Schedule</a></li>
            <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('search') }}">Book Tickets</a></li>
        </ul>

        {{-- Include user dropdown --}}
        <x-user-dropdown />
    </div>
</nav>