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

    <x-navbar />

    <div class="container mt-4">
        <h3 class="mb-4 text-center">Available Trains</h3>
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Search Summary -->
        @if(isset($searchParams))
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <strong>Search Results for:</strong>
                    {{ $searchParams['from'] ?? 'Any' }} → {{ $searchParams['to'] ?? 'Any' }}
                    @if($searchParams['date'])
                        on {{ date('F j, Y', strtotime($searchParams['date'])) }}
                    @endif
                    @if($searchParams['class'])
                        ({{ ucfirst($searchParams['class']) }} Class)
                    @endif
                </div>
            </div>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover custom-table mx-auto">
                <thead class="table-light">
                    <tr>
                        <th>Train Name</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Departure Time</th>
                        <th>Arrival Time</th>
                        <th>Duration</th>
                        <th>Seats Available</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->train->train_name ?? 'N/A' }}</td>
                        <td>{{ $schedule->sourceStation->name ?? 'N/A' }}</td>
                        <td>{{ $schedule->destinationStation->name ?? 'N/A' }}</td>
                        <td>{{ $schedule->departure_time->format('Y-m-d H:i A') }}</td>
                        <td>{{ $schedule->arrival_time->format('Y-m-d H:i A') }}</td>
                        <td>{{ $schedule->formatted_duration }}</td>
                        <td>{{ $schedule->available_seats }}</td>
                        <td>
                            @php
                                $twoHoursFromNow = now()->addHours(2);
                                $isWithinTwoHours = $schedule->departure_time <= $twoHoursFromNow;
                                $hasAvailableSeats = $schedule->available_seats > 0;
                                $isPastDeparture = $schedule->departure_time <= now();
                            @endphp
                            @if($isPastDeparture)
                                <span class="badge bg-secondary">Departed</span>
                            @elseif($isWithinTwoHours)
                                <span class="badge bg-warning text-dark">Booking Closed</span>
                            @elseif(!$hasAvailableSeats)
                                <span class="badge bg-danger">Fully Booked</span>
                            @else
                                <form action="{{ route('booking.start') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="schedule_id" value="{{ $schedule->schedule_id }}">
                                    <button type="submit" class="btn book-btn">Book Now</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            <div class="py-4">
                                <h5>No trains found</h5>
                                <p class="text-muted">Try adjusting your search criteria or select a different date.</p>
                                <a href="{{ route('search') }}" class="btn btn-primary">Search Again</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($schedules) && $schedules->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $schedules->appends(request()->query())->links('pagination.bootstrap-5') }}
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        @if(session('error'))
            alert('{{ session('error') }}');
        @endif
    </script>
</body>

</html>