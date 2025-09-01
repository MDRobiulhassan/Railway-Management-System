<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - NID Database</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/nid.css') }}">
</head>

<body>
    <x-navbar />

    <div class="container">
        <h1>NID Database Management</h1>

        <div class="mb-3 d-flex justify-content-between align-items-center">
            <input type="text" id="searchInput" class="form-control w-25" placeholder="Search by NID or name...">
            <button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#addNIDModal">
                <i class="fa-solid fa-plus"></i> Add NID
            </button>
        </div>

        <div class="table-container">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>NID ID</th>
                        <th>NID Number</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="nidTableBody">
                    <tr>
                        <td>1</td>
                        <td>1234567890</td>
                        <td>John Doe</td>
                        <td>1990-05-01</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal"
                                data-bs-target="#editNIDModal">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add NID Modal -->
    <div class="modal fade" id="addNIDModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addNIDForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New NID</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="add_nid_number" class="form-label">NID Number</label>
                            <input type="text" class="form-control" id="add_nid_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="add_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="add_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="add_dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="add_dob" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add NID</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit NID Modal -->
    <div class="modal fade" id="editNIDModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editNIDForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit NID</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nid_number" class="form-label">NID Number</label>
                            <input type="text" class="form-control" id="edit_nid_number">
                        </div>
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="edit_name">
                        </div>
                        <div class="mb-3">
                            <label for="edit_dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="edit_dob">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Update NID</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const nidTableBody = document.getElementById('nidTableBody');
        let currentEditRow = null;

        // Add NID
        document.getElementById('addNIDForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const nidNumber = document.getElementById('add_nid_number').value;
            const name = document.getElementById('add_name').value;
            const dob = document.getElementById('add_dob').value;

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${nidTableBody.children.length + 1}</td>
                <td>${nidNumber}</td>
                <td>${name}</td>
                <td>${dob}</td>
                <td>
                    <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#editNIDModal">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                </td>
            `;
            nidTableBody.appendChild(row);
            this.reset();
            bootstrap.Modal.getInstance(document.getElementById('addNIDModal')).hide();
        });

        // Edit & Delete
        nidTableBody.addEventListener('click', function (e) {
            const row = e.target.closest('tr');
            if (e.target.classList.contains('edit-btn')) {
                currentEditRow = row;
                document.getElementById('edit_nid_number').value = row.children[1].textContent;
                document.getElementById('edit_name').value = row.children[2].textContent;
                document.getElementById('edit_dob').value = row.children[3].textContent;
            }
            if (e.target.classList.contains('delete-btn')) {
                if (confirm('Delete this NID?')) row.remove();
            }
        });

        document.getElementById('editNIDForm').addEventListener('submit', function (e) {
            e.preventDefault();
            if (!currentEditRow) return;
            currentEditRow.children[1].textContent = document.getElementById('edit_nid_number').value;
            currentEditRow.children[2].textContent = document.getElementById('edit_name').value;
            currentEditRow.children[3].textContent = document.getElementById('edit_dob').value;
            bootstrap.Modal.getInstance(document.getElementById('editNIDModal')).hide();
        });

        // Search
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const term = this.value.toLowerCase();
            nidTableBody.querySelectorAll('tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });
    </script>
</body>

</html>