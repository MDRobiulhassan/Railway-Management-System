<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/bookings.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <x-navbar/>

    <div class="container">
        <h1 class="mb-4">Booking Management</h1>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="mb-3 d-flex justify-content-between align-items-center">
            <input type="text" id="searchInput" class="form-control w-25" placeholder="Search bookings...">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                <i class="fa-solid fa-plus"></i> Add Booking
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Booking ID</th>
                        <th>User ID</th>
                        <th>Train ID</th>
                        <th>Booking Date</th>
                        <th>Status</th>
                        <th>Total Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="bookingTableBody">
                    @forelse($bookings as $b)
                    <tr>
                        <td>{{ $b->booking_id }}</td>
                        <td>#{{ $b->user_id }} - {{ $b->user->name ?? 'User' }}</td>
                        <td>#{{ $b->train_id }} - {{ $b->train->train_name ?? 'Train' }}</td>
                        <td>{{ optional($b->booking_date)->format('Y-m-d H:i') }}</td>
                        <td>
                            @php $badge = $b->status==='confirmed'?'success':($b->status==='cancelled'?'danger':'warning'); @endphp
                            <span class="badge bg-{{ $badge }}">{{ ucfirst($b->status) }}</span>
                        </td>
                        <td>{{ number_format($b->total_amount,2) }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#addBookingModal" data-booking='@json($b)'>Edit</button>
                            <form action="{{ route('admin.bookings.destroy', $b) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this booking?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No bookings found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @isset($bookings)
        <div class="mt-3">{{ $bookings->links() }}</div>
        @endisset
    </div>

    <!-- Add Booking Modal -->
    <div class="modal fade" id="addBookingModal" tabindex="-1" aria-labelledby="addBookingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addBookingForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="bookingFormMethod" value="PUT">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBookingModalLabel">Add Booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">User ID</label>
                            <input type="number" class="form-control" id="user_id" name="user_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="train_id" class="form-label">Train ID</label>
                            <input type="number" class="form-control" id="train_id" name="train_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="booking_date" class="form-label">Booking Date</label>
                            <input type="datetime-local" class="form-control" id="booking_date" name="booking_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="total_amount" class="form-label">Total Amount</label>
                            <input type="number" class="form-control" id="total_amount" name="total_amount" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add Booking</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const term = this.value.toLowerCase();
            document.getElementById('bookingTableBody').querySelectorAll('tr').forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(term) ? '' : 'none';
            });
        });

        // Fill edit form
        document.querySelectorAll('[data-booking]').forEach(btn => {
            btn.addEventListener('click', function () {
                const b = JSON.parse(this.getAttribute('data-booking'));
                const form = document.getElementById('addBookingForm');
                form.action = `/adminpanel/bookings/${b.booking_id}`;
                document.getElementById('bookingFormMethod').value = 'PUT';
                document.getElementById('user_id').value = b.user_id;
                document.getElementById('train_id').value = b.train_id;
                const dt = new Date(b.booking_date);
                const pad = n => String(n).padStart(2,'0');
                const local = `${dt.getFullYear()}-${pad(dt.getMonth()+1)}-${pad(dt.getDate())}T${pad(dt.getHours())}:${pad(dt.getMinutes())}`;
                document.getElementById('booking_date').value = local;
                document.getElementById('status').value = b.status;
                document.getElementById('total_amount').value = b.total_amount;
                document.getElementById('addBookingModalLabel').textContent = 'Edit Booking';
                form.querySelector('.btn-success').textContent = 'Update Booking';
            });
        });
    </script>
</body>

</html>