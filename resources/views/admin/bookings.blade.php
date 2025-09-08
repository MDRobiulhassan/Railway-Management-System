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
                        <th>User Name</th>
                        <th>Train Name</th>
                        <th>Booking Date</th>
                        <th>Status</th>
                        <th>Total Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="bookingTableBody">
                    <!-- Example row -->
                    <tr>
                        <td>1</td>
                        <td>John Doe</td>
                        <td>Express 101</td>
                        <td>2025-09-02 10:30</td>
                        <td><span class="badge bg-success">Confirmed</span></td>
                        <td>250.00</td>
                        <td>
                            <button class="btn btn-sm btn-warning">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Jane Smith</td>
                        <td>Local 202</td>
                        <td>2025-09-03 14:00</td>
                        <td><span class="badge bg-warning">Pending</span></td>
                        <td>180.00</td>
                        <td>
                            <button class="btn btn-sm btn-warning">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Booking Modal -->
    <div class="modal fade" id="addBookingModal" tabindex="-1" aria-labelledby="addBookingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addBookingForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBookingModalLabel">Add Booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="booking_id" class="form-label">Booking ID</label>
                            <input type="number" class="form-control" id="booking_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="user_name" class="form-label">User Name</label>
                            <input type="text" class="form-control" id="user_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="train_name" class="form-label">Train Name</label>
                            <select class="form-select" id="train_name" required>
                                <option value="" disabled selected>Select Train</option>
                                <option value="Express 101">Express 101</option>
                                <option value="Local 202">Local 202</option>
                                <option value="Fast Track 303">Fast Track 303</option>
                                <!-- Add more trains as needed -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="booking_date" class="form-label">Booking Date</label>
                            <input type="datetime-local" class="form-control" id="booking_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Payment Status</label>
                            <select class="form-select" id="status" required>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="total_amount" class="form-label">Total Amount</label>
                            <input type="number" class="form-control" id="total_amount" step="0.01" min="0" required>
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
        const bookingTableBody = document.getElementById('bookingTableBody');
        const addBookingForm = document.getElementById('addBookingForm');

        addBookingForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const bookingId = document.getElementById('booking_id').value;
            const userName = document.getElementById('user_name').value;
            const trainName = document.getElementById('train_name').value;
            const bookingDate = document.getElementById('booking_date').value;
            const status = document.getElementById('status').value;
            const totalAmount = document.getElementById('total_amount').value;

            const statusBadge = status === 'confirmed' ? 'success' :
                status === 'cancelled' ? 'danger' : 'warning';

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${bookingId}</td>
                <td>${userName}</td>
                <td>${trainName}</td>
                <td>${bookingDate}</td>
                <td><span class="badge bg-${statusBadge}">${status.charAt(0).toUpperCase() + status.slice(1)}</span></td>
                <td>${parseFloat(totalAmount).toFixed(2)}</td>
                <td>
                    <button class="btn btn-sm btn-warning">Edit</button>
                    <button class="btn btn-sm btn-danger">Delete</button>
                </td>
            `;
            bookingTableBody.appendChild(newRow);

            addBookingForm.reset();
            const addModal = bootstrap.Modal.getInstance(document.getElementById('addBookingModal'));
            addModal.hide();
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const term = this.value.toLowerCase();
            bookingTableBody.querySelectorAll('tr').forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(term) ? '' : 'none';
            });
        });
    </script>
</body>

</html>