<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Food Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/food_items.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <x-navbar />

    <div class="container">
        <h1>Food Management</h1>

        <div class="mb-3 d-flex justify-content-between align-items-center">
            <input type="text" id="searchInput" class="form-control w-25" placeholder="Search food items...">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFoodModal">
                <i class="fa-solid fa-plus"></i> Add Food Item
            </button>
        </div>

        <div class="table-container">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Food ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Availability</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="foodTableBody">
                    @forelse($foodItems as $food)
                        <tr>
                            <td>{{ $food->food_id }}</td>
                            <td>{{ $food->name }}</td>
                            <td>{{ $food->category ?? '-' }}</td>
                            <td>{{ $food->description ?? '-' }}</td>
                            <td>৳{{ number_format($food->price ?? 0, 2) }}</td>
                            <td>
                                <span class="badge {{ $food->availability ? 'bg-success' : 'bg-danger' }}">{{ $food->availability ? 'Available' : 'Not Available' }}</span>
                            </td>
                            <td>
                                @if($food->image)
                                    <img src="{{ asset('storage/' . $food->image) }}" alt="{{ $food->name }}" style="height:40px;object-fit:cover;">
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-warning edit-food-btn" data-bs-toggle="modal"
                                        data-bs-target="#editFoodModal" data-food-id="{{ $food->food_id }}">Edit</button>
                                    <form action="{{ route('admin.food_items.destroy', $food->food_id) }}" method="POST" style="display:inline;"
                                        onsubmit="return confirm('Delete this food item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No food items found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Food Modal -->
    <div class="modal fade" id="addFoodModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addFoodForm" method="POST" action="{{ route('admin.food_items.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Food Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Food Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <input type="text" class="form-control" id="category" name="category">
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" step="0.01" class="form-control" id="price" name="price" min="0">
                                </div>
                                <div class="mb-3">
                                    <label for="availability" class="form-label">Availability</label>
                                    <select class="form-select" id="availability" name="availability">
                                        <option value="1">Available</option>
                                        <option value="0">Not Available</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add Food Item</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Food Modal -->
    <div class="modal fade" id="editFoodModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editFoodForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Food Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_name" class="form-label">Food Name</label>
                                    <input type="text" class="form-control" id="edit_name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_category" class="form-label">Category</label>
                                    <input type="text" class="form-control" id="edit_category" name="category">
                                </div>
                                <div class="mb-3">
                                    <label for="edit_price" class="form-label">Price</label>
                                    <input type="number" step="0.01" class="form-control" id="edit_price" name="price" min="0"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_availability" class="form-label">Availability</label>
                                    <select class="form-select" id="edit_availability" name="availability">
                                        <option value="1">Available</option>
                                        <option value="0">Not Available</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_description" class="form-label">Description</label>
                                    <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_image" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
                                    <small class="form-text text-muted">Leave empty to keep current image</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Update Food Item</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script>
        const foodTableBody = document.getElementById('foodTableBody');
        const editForm = document.getElementById('editFoodForm');

        // Search
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const term = this.value.toLowerCase();
            foodTableBody.querySelectorAll('tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        // Open edit modal and load data
        document.querySelectorAll('.edit-food-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-food-id');
                fetch(`/adminpanel/food_items/${id}/edit`)
                    .then(r => r.json())
                    .then(f => {
                        document.getElementById('edit_name').value = f.name;
                        document.getElementById('edit_category').value = f.category || '';
                        document.getElementById('edit_description').value = f.description || '';
                        document.getElementById('edit_price').value = f.price || 0;
                        document.getElementById('edit_availability').value = f.availability ? '1' : '0';
                        editForm.action = `/adminpanel/food_items/${id}`;
                    })
                    .catch(() => alert('Failed to load food item'));
            });
        });

        // Reset edit form on close
        document.getElementById('editFoodModal').addEventListener('hidden.bs.modal', function () {
            editForm.reset();
        });
    </script>
</body>

</html>