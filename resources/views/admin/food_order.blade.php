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

    <div class="container mt-4">
        <h1>Food Order Management</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form class="mb-3 d-flex justify-content-between align-items-center">
            <input type="text" id="searchInput" class="form-control w-25"
                placeholder="Search by user, food, or order id...">
            <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal"
                data-bs-target="#addOrderModal">
                <i class="fa-solid fa-plus"></i> Add Food Order
            </button>
        </form>

        @forelse($foodOrders as $bookingId => $orders)
            @if(isset($bookings[$bookingId]) && $orders->isNotEmpty())
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            Booking #{{ $bookingId }}
                            <small class="ms-2">{{ $bookings[$bookingId]->user->name ?? 'N/A' }}</small>
                            <span class="badge bg-light text-dark ms-2">
                                {{ $orders->count() }} items
                            </span>
                        </h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Food Item</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $bookingTotal = 0;
                                @endphp
                                @foreach($orders as $order)
                                    @php
                                        $itemTotal = optional($order->foodItem)->price * $order->quantity;
                                        $bookingTotal += $itemTotal;
                                    @endphp
                                    <tr>
                                        <td>{{ $order->order_id }}</td>
                                        <td>{{ $order->foodItem->name ?? 'N/A' }}</td>
                                        <td>৳{{ number_format(optional($order->foodItem)->price, 2) }}</td>
                                        <td>{{ $order->quantity }}</td>
                                        <td>৳{{ number_format($itemTotal, 2) }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-warning edit-btn" 
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editOrderModal" 
                                                    data-id="{{ $order->order_id }}">
                                                    Edit
                                                </button>
                                                <form action="{{ route('admin.food_order.destroy', $order->order_id) }}" 
                                                    method="POST" style="display:inline;"
                                                    onsubmit="return confirm('Delete this order?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="table-secondary">
                                    <td colspan="4" class="text-end fw-bold">Booking Total:</td>
                                    <td class="fw-bold">৳{{ number_format($bookingTotal, 2) }}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @empty
            <div class="alert alert-info">No food orders found</div>
        @endforelse
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
                        <!-- Booking Dropdown -->
                        <div class="mb-3">
                            <label class="form-label">Booking</label>
                            <select id="booking_select" name="booking_id" class="form-select" required>
                                <option value="" disabled selected>Select Booking</option>
                                @foreach($bookings as $b)
                                    <option value="{{ $b->booking_id }}">#{{ $b->booking_id }} - {{ $b->user->name ?? 'User' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Food Item</label>
                            <select id="food_input" name="food_id" class="form-select" required>
                                <option value="" disabled selected>Select Food</option>
                                @foreach($foodItems as $f)
                                    <option value="{{ $f->food_id }}" data-price="{{ $f->price }}">{{ $f->name }} (৳{{ number_format($f->price, 2) }})</option>
                                @endforeach
                            </select>
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
                        <!-- Booking Dropdown -->
                        <div class="mb-3">
                            <label class="form-label">Booking</label>
                            <select id="edit_booking_select" name="booking_id" class="form-select" required>
                                <option value="" disabled>Select Booking</option>
                                @foreach($bookings as $b)
                                    <option value="{{ $b->booking_id }}">#{{ $b->booking_id }} - {{ $b->user->name ?? 'User' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Food Item</label>
                            <select id="edit_food_input" name="food_id" class="form-select" required>
                                <option value="" disabled>Select Food</option>
                                @foreach($foodItems as $f)
                                    <option value="{{ $f->food_id }}" data-price="{{ $f->price }}">{{ $f->name }} (৳{{ number_format($f->price, 2) }})</option>
                                @endforeach
                            </select>
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
        const editForm = document.getElementById('editOrderForm');

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const term = this.value.toLowerCase();
            ordersTableBody.querySelectorAll('tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        // Open edit and load
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                fetch(`/adminpanel/food_order/${id}/edit`)
                    .then(r => r.json())
                    .then(o => {
                        document.getElementById('edit_booking_select').value = o.booking_id;
                        document.getElementById('edit_food_input').value = o.food_id;
                        document.getElementById('edit_quantity_input').value = o.quantity;
                        editForm.action = `/adminpanel/food_order/${id}`;
                    })
                    .catch(() => alert('Failed to load order'));
            });
        });
    </script>
</body>

</html>