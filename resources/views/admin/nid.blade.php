<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - NID Database</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/nid.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <x-navbar />

    <div class="container">
        <h1>NID Database Management</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
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
            <input type="text" id="searchInput" class="form-control w-25" placeholder="Search by NID or name...">
            <button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#addNIDModal">
                <i class="fa-solid fa-plus"></i> Add NID
            </button>
        </div>

        <div class="table-container">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>User ID</th>
                        <th>NID Number</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="nidTableBody">
                    @forelse($nids as $n)
                        <tr>
                            <td>{{ $n->user_id }}</td>
                            <td>{{ $n->nid_number }}</td>
                            <td>{{ $n->name }}</td>
                            <td>{{ $n->dob ? $n->dob->format('Y-m-d') : '-' }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#editNIDModal" data-id="{{ $n->user_id }}">Edit</button>
                                    <form action="{{ route('admin.nid.destroy', $n->user_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this NID record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No NID records found</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($nids->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $nids->links('pagination.bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add NID Modal -->
    <div class="modal fade" id="addNIDModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addNIDForm" method="POST" action="{{ route('admin.nid.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New NID</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="add_nid_number" class="form-label">NID Number</label>
                            <input type="text" class="form-control" id="add_nid_number" name="nid_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="add_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="add_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="add_dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="add_dob" name="dob" required>
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
                <form id="editNIDForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit NID</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nid_number" class="form-label">NID Number</label>
                            <input type="text" class="form-control" id="edit_nid_number" name="nid_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="edit_dob" name="dob" required>
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
        const editForm = document.getElementById('editNIDForm');

        // Search
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const term = this.value.toLowerCase();
            nidTableBody.querySelectorAll('tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        // Open edit modal
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                fetch(`/adminpanel/nid/${id}/edit`)
                    .then(r => r.json())
                    .then(n => {
                        document.getElementById('edit_nid_number').value = n.nid_number;
                        document.getElementById('edit_name').value = n.name;
                        document.getElementById('edit_dob').value = n.dob;
                        editForm.action = `/adminpanel/nid/${id}`;
                    })
                    .catch(() => alert('Failed to load NID record'));
            });
        });

        // Reset edit form on modal close
        document.getElementById('editNIDModal').addEventListener('hidden.bs.modal', function () {
            editForm.reset();
            editForm.removeAttribute('action');
        });
    </script>
</body>

</html>