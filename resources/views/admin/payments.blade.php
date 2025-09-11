<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Payments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/payments.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <x-navbar />

    <div class="container">
        <h1>Payment Management</h1>

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
            <input type="text" id="searchInput" class="form-control w-25" placeholder="Search payments...">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                <i class="fa-solid fa-plus"></i> Add Payment
            </button>
        </div>

        <div class="table-container">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Payment ID</th>
                        <th>Booking</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Transaction ID</th>
                        <th>Paid At</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="paymentTableBody">
                    @forelse($payments as $p)
                        <tr>
                            <td>{{ $p->payment_id }}</td>
                            <td>Booking #{{ $p->booking_id }} - {{ $p->booking->user->name ?? 'N/A' }}</td>
                            <td>৳{{ number_format($p->amount, 2) }}</td>
                            <td>{{ $p->payment_method }}</td>
                            <td><span class="badge {{ $p->payment_status === 'completed' ? 'bg-success' : ($p->payment_status === 'failed' ? 'bg-danger' : 'bg-warning') }}">{{ ucfirst($p->payment_status) }}</span></td>
                            <td>{{ $p->transaction_id ?? '-' }}</td>
                            <td>{{ $p->paid_at ? $p->paid_at->format('Y-m-d H:i') : '-' }}</td>
                            <td>{{ $p->created_at ? $p->created_at->format('Y-m-d H:i') : '-' }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#editPaymentModal" data-id="{{ $p->payment_id }}">Edit</button>
                                    <form action="{{ route('admin.payments.destroy', $p->payment_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this payment?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No payments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Payment Modal -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addPaymentForm" method="POST" action="{{ route('admin.payments.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_booking_id" class="form-label">Booking ID</label>
                                <input type="number" class="form-control" id="add_booking_id" name="booking_id" required>
                            </div>
                            <div class="mb-3">
                                <label for="add_amount" class="form-label">Amount</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="add_amount" name="amount" required>
                            </div>
                            <div class="mb-3">
                                <label for="add_method" class="form-label">Payment Method</label>
                                <select class="form-select" id="add_method" name="payment_method" required>
                                    <option value="Card">Card</option>
                                    <option value="Bkash">Bkash</option>
                                    <option value="Cash">Cash</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_status" class="form-label">Status</label>
                                <select class="form-select" id="add_status" name="payment_status" required>
                                    <option value="completed">Completed</option>
                                    <option value="pending">Pending</option>
                                    <option value="failed">Failed</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="add_transaction_id" class="form-label">Transaction ID</label>
                                <input type="text" class="form-control" id="add_transaction_id" name="transaction_id">
                            </div>
                            <div class="mb-3">
                                <label for="add_paid_at" class="form-label">Paid At</label>
                                <input type="datetime-local" class="form-control" id="add_paid_at" name="paid_at">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add Payment</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Payment Modal -->
    <div class="modal fade" id="editPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editPaymentForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_booking_id" class="form-label">Booking ID</label>
                                <input type="number" class="form-control" id="edit_booking_id" name="booking_id" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_amount" class="form-label">Amount</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="edit_amount" name="amount" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_method" class="form-label">Payment Method</label>
                                <select class="form-select" id="edit_method" name="payment_method" required>
                                    <option value="Card">Card</option>
                                    <option value="Bkash">Bkash</option>
                                    <option value="Cash">Cash</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Status</label>
                                <select class="form-select" id="edit_status" name="payment_status" required>
                                    <option value="completed">Completed</option>
                                    <option value="pending">Pending</option>
                                    <option value="failed">Failed</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit_transaction_id" class="form-label">Transaction ID</label>
                                <input type="text" class="form-control" id="edit_transaction_id" name="transaction_id">
                            </div>
                            <div class="mb-3">
                                <label for="edit_paid_at" class="form-label">Paid At</label>
                                <input type="datetime-local" class="form-control" id="edit_paid_at" name="paid_at">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Update Payment</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script>
        const paymentTableBody = document.getElementById('paymentTableBody');
        const editForm = document.getElementById('editPaymentForm');

        // Search
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const term = this.value.toLowerCase();
            paymentTableBody.querySelectorAll('tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        // Open edit and load JSON
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                fetch(`/adminpanel/payments/${id}/edit`)
                    .then(r => r.json())
                    .then(p => {
                        document.getElementById('edit_booking_id').value = p.booking_id;
                        document.getElementById('edit_amount').value = p.amount;
                        document.getElementById('edit_method').value = p.payment_method;
                        document.getElementById('edit_status').value = p.payment_status;
                        document.getElementById('edit_transaction_id').value = p.transaction_id || '';
                        document.getElementById('edit_paid_at').value = p.paid_at || '';
                        editForm.action = `/adminpanel/payments/${id}`;
                    })
                    .catch(() => alert('Failed to load payment'));
            });
        });

        // Reset edit on hide
        document.getElementById('editPaymentModal').addEventListener('hidden.bs.modal', function () {
            editForm.reset();
        });
    </script>
</body>

</html>