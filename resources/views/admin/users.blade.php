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
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->user_id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-primary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>{{ $user->contact_number ?? 'N/A' }}</td>
                            <td>{{ $user->address ?? 'N/A' }}</td>
                            <td>{{ $user->dob ? $user->dob->format('Y-m-d') : 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $user->nid_verified ? 'bg-success' : 'bg-warning' }}">
                                    {{ $user->nid_verified ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-warning edit-user-btn" data-bs-toggle="modal"
                                        data-bs-target="#editUserModal" data-user-id="{{ $user->user_id }}">Edit</button>
                                    
                                    @if(auth()->id() != $user->user_id)
                                        <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" style="display:inline;"
                                            onsubmit="return confirm('Are you sure you want to delete {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-danger" disabled
                                            title="Cannot delete your own account">Delete</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($users->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
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
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="user" selected>User</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="dob" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="dob" name="dob" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nid_number" class="form-label">NID Number</label>
                                    <input type="text" class="form-control" id="nid_number" name="nid_number" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nid_verified" class="form-label">NID Verified</label>
                                    <select class="form-select" id="nid_verified" name="nid_verified">
                                        <option value="1" selected>Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
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
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
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
                                    <input type="text" class="form-control" id="edit_name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="edit_email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_role" class="form-label">Role</label>
                                    <select class="form-select" id="edit_role" name="role" required>
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_contact_number" class="form-label">Contact Number</label>
                                    <input type="text" class="form-control" id="edit_contact_number" name="contact_number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_address" class="form-label">Address</label>
                                    <textarea class="form-control" id="edit_address" name="address" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_dob" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="edit_dob" name="dob" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_nid_number" class="form-label">NID Number</label>
                                    <input type="text" class="form-control" id="edit_nid_number" name="nid_number" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_nid_verified" class="form-label">NID Verified</label>
                                    <select class="form-select" id="edit_nid_verified" name="nid_verified">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="edit_password" name="password"
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

        // Edit user functionality
        document.querySelectorAll('.edit-user-btn').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                
                // Fetch user data
                fetch(`/adminpanel/users/${userId}/edit`)
                    .then(response => response.json())
                    .then(user => {
                        // Populate form fields
                        document.getElementById('edit_name').value = user.name;
                        document.getElementById('edit_email').value = user.email;
                        document.getElementById('edit_role').value = user.role;
                        document.getElementById('edit_contact_number').value = user.contact_number || '';
                        document.getElementById('edit_address').value = user.address || '';
                        document.getElementById('edit_dob').value = user.dob;
                        document.getElementById('edit_nid_number').value = user.nid_number;
                        document.getElementById('edit_nid_verified').value = user.nid_verified ? '1' : '0';
                        document.getElementById('edit_password').value = '';
                        
                        // Update form action
                        document.getElementById('editUserForm').action = `/adminpanel/users/${userId}`;
                    })
                    .catch(error => {
                        console.error('Error fetching user data:', error);
                        alert('Error loading user data');
                    });
            });
        });
    </script>
</body>

</html>