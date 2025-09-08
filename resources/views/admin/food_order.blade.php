<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Food Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/food_order.css') }}">
</head>

<body>
    <x-navbar />

    <div class="container">
        <h1>Food Order Management</h1>

        <form id="addOrderFormUI" class="mb-3 d-flex justify-content-between align-items-center">
            <input type="text" id="searchInput" class="form-control w-25"
                placeholder="Search by user, food, or order id...">
            <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal"
                data-bs-target="#addOrderModal">
                <i class="fa-solid fa-plus"></i> Add Food Order
            </button>
        </form>

        <div class="table-container">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Booking</th>
                        <th>Food Item</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody">
                    <!-- Example row -->
                    <tr>
                        <td>1</td>
                        <td>#101 - John Doe</td>
                        <td>Pizza</td>
                        <td>2</td>
                        <td>$20.00</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal"
                                data-bs-target="#editOrderModal">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Order Modal -->
    <div class="modal fade" id="addOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addOrderForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Food Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Booking</label>
                            <input type="text" id="booking_input" class="form-control"
                                placeholder="#BookingID - User Name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Food Item</label>
                            <input type="text" id="food_input" class="form-control" placeholder="Food Name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" id="quantity_input" class="form-control" min="1" value="1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price per Unit</label>
                            <input type="number" id="price_input" class="form-control" min="0" step="0.01" value="0.00">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add Order</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Order Modal -->
    <div class="modal fade" id="editOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editOrderForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Food Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Booking</label>
                            <input type="text" id="edit_booking_input" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Food Item</label>
                            <input type="text" id="edit_food_input" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" id="edit_quantity_input" class="form-control" min="1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price per Unit</label>
                            <input type="number" id="edit_price_input" class="form-control" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Update Order</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const ordersTableBody = document.getElementById('ordersTableBody');
        let currentEditRow = null;

        // Search
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const term = this.value.toLowerCase();
            ordersTableBody.querySelectorAll('tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        // Add Order
        document.getElementById('addOrderForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const booking = document.getElementById('booking_input').value || '-';
            const food = document.getElementById('food_input').value || '-';
            const quantity = parseInt(document.getElementById('quantity_input').value) || 1;
            const price = parseFloat(document.getElementById('price_input').value) || 0;
            const total = (quantity * price).toFixed(2);

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${ordersTableBody.children.length + 1}</td>
                <td>${booking}</td>
                <td>${food}</td>
                <td>${quantity}</td>
                <td>$${total}</td>
                <td>
                    <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#editOrderModal">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                </td>
            `;
            ordersTableBody.appendChild(row);
            this.reset();
            bootstrap.Modal.getInstance(document.getElementById('addOrderModal')).hide();
        });

        // Edit/Delete
        ordersTableBody.addEventListener('click', function (e) {
            const row = e.target.closest('tr');
            if (e.target.classList.contains('edit-btn')) {
                currentEditRow = row;
                document.getElementById('edit_booking_input').value = row.children[1].textContent;
                document.getElementById('edit_food_input').value = row.children[2].textContent;
                document.getElementById('edit_quantity_input').value = row.children[3].textContent;
                document.getElementById('edit_price_input').value = (parseFloat(row.children[4].textContent.replace('$', '')) / parseInt(row.children[3].textContent)).toFixed(2);
            }
            if (e.target.classList.contains('delete-btn')) {
                if (confirm('Delete this order?')) row.remove();
            }
        });

        // Update Order
        document.getElementById('editOrderForm').addEventListener('submit', function (e) {
            e.preventDefault();
            if (!currentEditRow) return;
            const booking = document.getElementById('edit_booking_input').value || '-';
            const food = document.getElementById('edit_food_input').value || '-';
            const quantity = parseInt(document.getElementById('edit_quantity_input').value) || 1;
            const price = parseFloat(document.getElementById('edit_price_input').value) || 0;
            const total = (quantity * price).toFixed(2);

            currentEditRow.children[1].textContent = booking;
            currentEditRow.children[2].textContent = food;
            currentEditRow.children[3].textContent = quantity;
            currentEditRow.children[4].textContent = `$${total}`;

            bootstrap.Modal.getInstance(document.getElementById('editOrderModal')).hide();
        });
    </script>
</body>

</html>