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
                    <!-- Initial example row -->
                    <tr>
                        <td>1</td>
                        <td>Booking #101 - John Doe</td>
                        <td>$200.00</td>
                        <td>Card</td>
                        <td><span class="badge bg-success">Completed</span></td>
                        <td>TX12345</td>
                        <td>2025-09-02 10:30</td>
                        <td>2025-09-01 09:00</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal"
                                data-bs-target="#editPaymentModal">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Payment Modal -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addPaymentForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_payment_id" class="form-label">Payment ID</label>
                                <input type="number" class="form-control" id="add_payment_id" required>
                            </div>
                            <div class="mb-3">
                                <label for="add_booking" class="form-label">Booking</label>
                                <input type="text" class="form-control" id="add_booking"
                                    placeholder="Booking #ID - User Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="add_amount" class="form-label">Amount</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="add_amount" required>
                            </div>
                            <div class="mb-3">
                                <label for="add_method" class="form-label">Payment Method</label>
                                <select class="form-select" id="add_method" required>
                                    <option value="Card">Card</option>
                                    <option value="Bkash">Bkash</option>
                                    <option value="Cash">Cash</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_status" class="form-label">Status</label>
                                <select class="form-select" id="add_status" required>
                                    <option value="completed">Completed</option>
                                    <option value="pending">Pending</option>
                                    <option value="failed">Failed</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="add_transaction_id" class="form-label">Transaction ID</label>
                                <input type="text" class="form-control" id="add_transaction_id">
                            </div>
                            <div class="mb-3">
                                <label for="add_paid_at" class="form-label">Paid At</label>
                                <input type="datetime-local" class="form-control" id="add_paid_at">
                            </div>
                            <div class="mb-3">
                                <label for="add_created_at" class="form-label">Created At</label>
                                <input type="datetime-local" class="form-control" id="add_created_at">
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
                <form id="editPaymentForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_payment_id" class="form-label">Payment ID</label>
                                <input type="number" class="form-control" id="edit_payment_id" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_booking" class="form-label">Booking</label>
                                <input type="text" class="form-control" id="edit_booking" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_amount" class="form-label">Amount</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="edit_amount" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_method" class="form-label">Payment Method</label>
                                <select class="form-select" id="edit_method" required>
                                    <option value="Card">Card</option>
                                    <option value="Bkash">Bkash</option>
                                    <option value="Cash">Cash</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Status</label>
                                <select class="form-select" id="edit_status" required>
                                    <option value="completed">Completed</option>
                                    <option value="pending">Pending</option>
                                    <option value="failed">Failed</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit_transaction_id" class="form-label">Transaction ID</label>
                                <input type="text" class="form-control" id="edit_transaction_id">
                            </div>
                            <div class="mb-3">
                                <label for="edit_paid_at" class="form-label">Paid At</label>
                                <input type="datetime-local" class="form-control" id="edit_paid_at">
                            </div>
                            <div class="mb-3">
                                <label for="edit_created_at" class="form-label">Created At</label>
                                <input type="datetime-local" class="form-control" id="edit_created_at">
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
        let currentEditRow = null;

        // Add Payment
        document.getElementById('addPaymentForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const id = document.getElementById('add_payment_id').value;
            const booking = document.getElementById('add_booking').value;
            const amount = parseFloat(document.getElementById('add_amount').value).toFixed(2);
            const method = document.getElementById('add_method').value;
            const status = document.getElementById('add_status').value;
            const transaction = document.getElementById('add_transaction_id').value;
            const paidAt = document.getElementById('add_paid_at').value;
            const createdAt = document.getElementById('add_created_at').value;

            const badgeClass = status === 'completed' ? 'success' : status === 'failed' ? 'danger' : 'warning';

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${id}</td>
                <td>${booking}</td>
                <td>$${amount}</td>
                <td>${method}</td>
                <td><span class="badge bg-${badgeClass}">${status.charAt(0).toUpperCase() + status.slice(1)}</span></td>
                <td>${transaction || '-'}</td>
                <td>${paidAt || '-'}</td>
                <td>${createdAt || '-'}</td>
                <td>
                    <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#editPaymentModal">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                </td>
            `;
            paymentTableBody.appendChild(row);
            this.reset();
            bootstrap.Modal.getInstance(document.getElementById('addPaymentModal')).hide();
        });

        // Edit Payment
        paymentTableBody.addEventListener('click', function (e) {
            const row = e.target.closest('tr');
            if (e.target.classList.contains('edit-btn')) {
                currentEditRow = row;
                document.getElementById('edit_payment_id').value = row.children[0].textContent;
                document.getElementById('edit_booking').value = row.children[1].textContent;
                document.getElementById('edit_amount').value = parseFloat(row.children[2].textContent.replace('$', ''));
                document.getElementById('edit_method').value = row.children[3].textContent;
                document.getElementById('edit_status').value = row.children[4].textContent.toLowerCase();
                document.getElementById('edit_transaction_id').value = row.children[5].textContent === '-' ? '' : row.children[5].textContent;
                document.getElementById('edit_paid_at').value = row.children[6].textContent === '-' ? '' : row.children[6].textContent;
                document.getElementById('edit_created_at').value = row.children[7].textContent === '-' ? '' : row.children[7].textContent;
            }

            if (e.target.classList.contains('delete-btn')) {
                if (confirm('Delete this payment?')) row.remove();
            }
        });

        document.getElementById('editPaymentForm').addEventListener('submit', function (e) {
            e.preventDefault();
            if (!currentEditRow) return;

            const id = document.getElementById('edit_payment_id').value;
            const booking = document.getElementById('edit_booking').value;
            const amount = parseFloat(document.getElementById('edit_amount').value).toFixed(2);
            const method = document.getElementById('edit_method').value;
            const status = document.getElementById('edit_status').value;
            const transaction = document.getElementById('edit_transaction_id').value;
            const paidAt = document.getElementById('edit_paid_at').value;
            const createdAt = document.getElementById('edit_created_at').value;

            const badgeClass = status === 'completed' ? 'success' : status === 'failed' ? 'danger' : 'warning';

            currentEditRow.innerHTML = `
                <td>${id}</td>
                <td>${booking}</td>
                <td>$${amount}</td>
                <td>${method}</td>
                <td><span class="badge bg-${badgeClass}">${status.charAt(0).toUpperCase() + status.slice(1)}</span></td>
                <td>${transaction || '-'}</td>
                <td>${paidAt || '-'}</td>
                <td>${createdAt || '-'}</td>
                <td>
                    <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#editPaymentModal">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                </td>
            `;
            bootstrap.Modal.getInstance(document.getElementById('editPaymentModal')).hide();
        });

        // Search
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const term = this.value.toLowerCase();
            paymentTableBody.querySelectorAll('tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        // Reset forms on close
        document.getElementById('addPaymentModal').addEventListener('hidden.bs.modal', function () {
            this.querySelector('form').reset();
        });
        document.getElementById('editPaymentModal').addEventListener('hidden.bs.modal', function () {
            this.querySelector('form').reset();
        });
    </script>
</body>

</html>