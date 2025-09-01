<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/users.css') }}">
</head>

<body>
    <x-navbar />

    <div class="container">
        <h1>User Management</h1>

        <!-- Success & error messages -->
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            User added successfully
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <div class="mb-3 d-flex justify-content-between align-items-center">
            <input type="text" class="form-control w-25" id="searchInput" placeholder="Search users...">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fa-solid fa-plus"></i> Add User
            </button>
        </div>

        <div class="table-container">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                        <th>Date of Birth</th>
                        <th>NID Verified</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Hardcoded Users -->
                    <tr>
                        <td>1</td>
                        <td>Robiul Hassan</td>
                        <td>robiul@example.com</td>
                        <td><span class="badge bg-danger">Admin</span></td>
                        <td>+880123456789</td>
                        <td>Dhaka, Bangladesh</td>
                        <td>2002-05-15</td>
                        <td><span class="badge bg-success">Yes</span></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-user-btn" data-bs-toggle="modal"
                                    data-bs-target="#editUserModal" data-user="1">Edit</button>
                                <button class="btn btn-sm btn-danger" disabled
                                    title="Cannot delete your own account">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>John Doe</td>
                        <td>john@example.com</td>
                        <td><span class="badge bg-primary">Passenger</span></td>
                        <td>+880987654321</td>
                        <td>Chittagong, Bangladesh</td>
                        <td>2000-11-20</td>
                        <td><span class="badge bg-warning">No</span></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-user-btn" data-bs-toggle="modal"
                                    data-bs-target="#editUserModal" data-user="2">Edit</button>

                                <!-- Delete button with confirmation -->
                                <form action="#" method="POST" style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete John Doe?')">
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Jane Smith</td>
                        <td>jane@example.com</td>
                        <td><span class="badge bg-primary">Passenger</span></td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td><span class="badge bg-warning">No</span></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-user-btn" data-bs-toggle="modal"
                                    data-bs-target="#editUserModal" data-user="3">Edit</button>
                                <form action="#" method="POST" style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete Jane Smith?')">
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form fields (same as before) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select" id="role" required>
                                        <option value="passenger" selected>Passenger</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <input type="text" class="form-control" id="contact_number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="dob" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="dob" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nid_number" class="form-label">NID Number</label>
                                    <input type="text" class="form-control" id="nid_number" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nid_verified" class="form-label">NID Verified</label>
                                    <select class="form-select" id="nid_verified">
                                        <option value="1" selected>Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add User</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form fields (same as before) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="edit_name" value="John Doe" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="edit_email" value="john@example.com"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_role" class="form-label">Role</label>
                                    <select class="form-select" id="edit_role" required>
                                        <option value="passenger" selected>Passenger</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_contact_number" class="form-label">Contact Number</label>
                                    <input type="text" class="form-control" id="edit_contact_number"
                                        value="+880987654321">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_address" class="form-label">Address</label>
                                    <textarea class="form-control" id="edit_address"
                                        rows="3">Chittagong, Bangladesh</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_dob" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="edit_dob" value="2000-11-20" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_nid_number" class="form-label">NID Number</label>
                                    <input type="text" class="form-control" id="edit_nid_number" value="1234567890"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_nid_verified" class="form-label">NID Verified</label>
                                    <select class="form-select" id="edit_nid_verified">
                                        <option value="1">Yes</option>
                                        <option value="0" selected>No</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="edit_password"
                                        placeholder="Leave blank to keep current">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Update User</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
</body>

</html>