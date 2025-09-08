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
                    @forelse($orders as $o)
                    <tr>
                        <td>{{ $o->order_id }}</td>
                        <td>#{{ $o->booking_id }} - {{ $o->booking->user->name ?? '' }}</td>
                        <td>{{ $o->foodItem->name ?? '' }}</td>
                        <td>{{ $o->quantity }}</td>
                        <td>${{ number_format(($o->foodItem->price ?? 0) * $o->quantity, 2) }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#editOrderModal" data-order='@json($o)'>Edit</button>
                            <form action="{{ route('admin.food_order.destroy', $o) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this order?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger delete-btn" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No food orders found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @isset($orders)
        <div class="mt-3">{{ $orders->links() }}</div>
        @endisset
    </div>

    <!-- Add Order Modal -->
    <div class="modal fade" id="addOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addOrderForm" method="POST" action="{{ route('admin.food_order.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Food Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Booking ID</label>
                            <input type="number" id="booking_input" name="booking_id" class="form-control" placeholder="Booking ID" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Food Item ID</label>
                            <input type="number" id="food_input" name="food_id" class="form-control" placeholder="Food ID" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" id="quantity_input" name="quantity" class="form-control" min="1" value="1" required>
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
                <form id="editOrderForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Food Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Booking ID</label>
                            <input type="number" id="edit_booking_input" name="booking_id" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Food Item ID</label>
                            <input type="number" id="edit_food_input" name="food_id" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" id="edit_quantity_input" name="quantity" class="form-control" min="1" required>
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

        // Search
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const term = this.value.toLowerCase();
            ordersTableBody.querySelectorAll('tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        // Populate Edit
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const o = JSON.parse(this.getAttribute('data-order'));
                const form = document.getElementById('editOrderForm');
                form.action = `/adminpanel/food_order/${o.order_id}`;
                document.getElementById('edit_booking_input').value = o.booking_id;
                document.getElementById('edit_food_input').value = o.food_id;
                document.getElementById('edit_quantity_input').value = o.quantity;
            });
        });
    </script>
</body>

</html>