<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Train Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/search.css') }}" />
</head>

<body>
    <x-navbar />
    <div class="container mt-4">
        <h3 class="mb-4 text-center">Upcoming Train Schedule</h3>
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                                    <small class="d-block text-muted">Train departs in less than 2 hours</small>
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
                            <td colspan="8" class="text-center">No upcoming trains available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($schedules->hasPages())
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