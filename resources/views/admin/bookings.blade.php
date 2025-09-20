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
    <x-navbar />

    <div class="container mt-4">
        <h1 class="mb-4">Booking Management</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="mb-3 d-flex justify-content-between align-items-center">
            <input type="text" id="searchInput" class="form-control w-25" placeholder="Search bookings...">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal">
                <i class="fa-solid fa-plus"></i> Add Booking
            </button>
        </div>

        @forelse($bookingsBySchedule as $scheduleKey => $scheduleData)
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        {{ $scheduleData['train_name'] }} - {{ $scheduleData['route'] }}
                        <small class="ms-2">{{ $scheduleData['departure_time'] }}</small>
                        <span class="badge bg-light text-dark ms-2">
                            {{ $scheduleData['bookings']->count() }} bookings
                        </span>
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Booking ID</th>
                                <th>User</th>
                                <th>Booking Date</th>
                                <th>Status</th>
                                <th>Total Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($scheduleData['bookings'] as $booking)
                                <tr>
                                    <td>{{ $booking->booking_id }}</td>
                                    <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                    <td>{{ $booking->booking_date ? $booking->booking_date->format('Y-m-d H:i') : 'N/A' }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($booking->status === 'confirmed') bg-success
                                            @elseif($booking->status === 'cancelled') bg-danger
                                            @else bg-warning text-dark
                                            @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td>৳{{ number_format($booking->total_amount, 2) }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-warning edit-booking-btn" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#bookingModal" 
                                                data-booking-id="{{ $booking->booking_id }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.bookings.destroy', $booking->booking_id) }}" 
                                                method="POST" style="display:inline;"
                                                onsubmit="return confirm('Delete this booking?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($loop->last && $bookings->hasPages())
                        <div class="card-footer">
                            <div class="d-flex justify-content-center">
                                {{ $bookings->links('pagination.bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="alert alert-info">No bookings found</div>
        @endforelse
    </div>

    <!-- Add/Edit Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="bookingForm" method="POST">
                    @csrf
                    <div id="method-field"></div>
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add Booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">User ID</label>
                            <input type="number" class="form-control" name="user_id" id="user_id" required>
                            <small class="text-muted">Enter existing User ID</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Train ID</label>
                            <input type="number" class="form-control" name="train_id" id="train_id" required>
                            <small class="text-muted">Enter existing Train ID</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Booking Date</label>
                            <input type="datetime-local" class="form-control" name="booking_date" id="booking_date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="status" required>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total Amount</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="total_amount" id="total_amount" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const bookingForm = document.getElementById('bookingForm');
        const methodField = document.getElementById('method-field');
        const modalTitle = document.getElementById('modalTitle');

        // Default to create
        document.addEventListener('DOMContentLoaded', function () {
            bookingForm.action = '/adminpanel/bookings';
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const term = this.value.toLowerCase();
            document.querySelectorAll('#bookingTableBody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        // Edit booking
        document.querySelectorAll('.edit-booking-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-booking-id');
                modalTitle.textContent = 'Edit Booking';
                methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';

                fetch(`/adminpanel/bookings/${id}/edit`)
                    .then(r => r.json())
                    .then(b => {
                        document.getElementById('user_id').value = b.user_id;
                        document.getElementById('train_id').value = b.train_id;
                        document.getElementById('booking_date').value = b.booking_date;
                        document.getElementById('status').value = b.status;
                        document.getElementById('total_amount').value = b.total_amount;
                        bookingForm.action = `/adminpanel/bookings/${id}`;
                    })
                    .catch(() => alert('Failed to load booking'));
            });
        });

        document.getElementById('bookingModal').addEventListener('hidden.bs.modal', function () {
            modalTitle.textContent = 'Add Booking';
            methodField.innerHTML = '';
            bookingForm.action = '/adminpanel/bookings';
            bookingForm.reset();
        });
    </script>
</body>

</html>