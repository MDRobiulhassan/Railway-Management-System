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
                    <!-- Example initial row -->
                    <tr>
                        <td>1</td>
                        <td>Pizza</td>
                        <td>Fast Food</td>
                        <td>Cheese Pizza</td>
                        <td>$10.00</td>
                        <td><span class="badge bg-success">Available</span></td>
                        <td>-</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-food-btn" data-bs-toggle="modal"
                                data-bs-target="#editFoodModal">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Food Modal -->
    <div class="modal fade" id="addFoodModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addFoodForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Food Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Food Name</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <input type="text" class="form-control" id="category">
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" step="0.01" class="form-control" id="price" min="0">
                                </div>
                                <div class="mb-3">
                                    <label for="availability" class="form-label">Availability</label>
                                    <select class="form-select" id="availability">
                                        <option value="1">Available</option>
                                        <option value="0">Not Available</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="image" accept="image/*">
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
                <form id="editFoodForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Food Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_name" class="form-label">Food Name</label>
                                    <input type="text" class="form-control" id="edit_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_category" class="form-label">Category</label>
                                    <input type="text" class="form-control" id="edit_category">
                                </div>
                                <div class="mb-3">
                                    <label for="edit_price" class="form-label">Price</label>
                                    <input type="number" step="0.01" class="form-control" id="edit_price" min="0"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_availability" class="form-label">Availability</label>
                                    <select class="form-select" id="edit_availability">
                                        <option value="1">Available</option>
                                        <option value="0">Not Available</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_description" class="form-label">Description</label>
                                    <textarea class="form-control" id="edit_description" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_image" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="edit_image" accept="image/*">
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
        let currentEditRow = null;

        // Search
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const term = this.value.toLowerCase();
            foodTableBody.querySelectorAll('tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        // Add Food Item
        document.getElementById('addFoodForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const name = document.getElementById('name').value;
            const category = document.getElementById('category').value || '-';
            const price = parseFloat(document.getElementById('price').value || 0).toFixed(2);
            const availability = document.getElementById('availability').value === '1';
            const description = document.getElementById('description').value || '-';
            const image = document.getElementById('image').files[0] ? 'Image' : '-';

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${foodTableBody.children.length + 1}</td>
                <td>${name}</td>
                <td>${category}</td>
                <td>${description}</td>
                <td>$${price}</td>
                <td><span class="badge ${availability ? 'bg-success' : 'bg-danger'}">${availability ? 'Available' : 'Not Available'}</span></td>
                <td>${image}</td>
                <td>
                    <button class="btn btn-sm btn-warning edit-food-btn" data-bs-toggle="modal" data-bs-target="#editFoodModal">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                </td>
            `;
            foodTableBody.appendChild(row);
            this.reset();
            bootstrap.Modal.getInstance(document.getElementById('addFoodModal')).hide();
        });

        // Edit / Delete
        foodTableBody.addEventListener('click', function (e) {
            const row = e.target.closest('tr');
            if (e.target.classList.contains('edit-food-btn')) {
                currentEditRow = row;
                document.getElementById('edit_name').value = row.children[1].textContent;
                document.getElementById('edit_category').value = row.children[2].textContent;
                document.getElementById('edit_description').value = row.children[3].textContent;
                document.getElementById('edit_price').value = parseFloat(row.children[4].textContent.replace('$', ''));
                document.getElementById('edit_availability').value = row.children[5].textContent.includes('Available') ? '1' : '0';
            }
            if (e.target.classList.contains('delete-btn')) {
                if (confirm('Delete this item?')) {
                    row.remove();
                    // Re-number IDs
                    Array.from(foodTableBody.children).forEach((r, i) => r.children[0].textContent = i + 1);
                }
            }
        });

        document.getElementById('editFoodForm').addEventListener('submit', function (e) {
            e.preventDefault();
            if (!currentEditRow) return;
            const availability = document.getElementById('edit_availability').value === '1';
            currentEditRow.children[1].textContent = document.getElementById('edit_name').value;
            currentEditRow.children[2].textContent = document.getElementById('edit_category').value || '-';
            currentEditRow.children[3].textContent = document.getElementById('edit_description').value || '-';
            currentEditRow.children[4].textContent = `$${parseFloat(document.getElementById('edit_price').value || 0).toFixed(2)}`;
            currentEditRow.children[5].innerHTML = `<span class="badge ${availability ? 'bg-success' : 'bg-danger'}">${availability ? 'Available' : 'Not Available'}</span>`;
            bootstrap.Modal.getInstance(document.getElementById('editFoodModal')).hide();
        });

        // Reset modals on close
        document.getElementById('addFoodModal').addEventListener('hidden.bs.modal', function () {
            this.querySelector('form').reset();
        });
        document.getElementById('editFoodModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('editFoodForm').reset();
        });
    </script>
</body>

</html>