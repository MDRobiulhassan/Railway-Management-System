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
                    @forelse($nids as $n)
                    <tr>
                        <td>{{ $n->user_id }}</td>
                        <td>{{ $n->nid_number }}</td>
                        <td>{{ $n->name }}</td>
                        <td>{{ optional($n->dob)->format('Y-m-d') }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#editNIDModal" data-nid='@json($n)'>Edit</button>
                            <form action="{{ route('admin.nid') }}" method="POST" class="d-inline-block" onsubmit="return false;"></form>
                            <form action="{{ route('admin.nid') }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this NID?')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $n->user_id }}">
                                <button class="btn btn-sm btn-danger delete-btn" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No NID records found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @isset($nids)
        <div class="mt-3">{{ $nids->links() }}</div>
        @endisset
    </div>

    <!-- Add NID Modal -->
    <div class="modal fade" id="addNIDModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addNIDForm" method="POST" action="{{ route('admin.nid') }}">
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
                            <input type="text" class="form-control" id="edit_nid_number" name="nid_number">
                        </div>
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="edit_dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="edit_dob" name="dob">
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
        // Populate edit modal
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const n = JSON.parse(this.getAttribute('data-nid'));
                const form = document.getElementById('editNIDForm');
                form.action = `/adminpanel/nid/${n.user_id}`;
                document.getElementById('edit_nid_number').value = n.nid_number;
                document.getElementById('edit_name').value = n.name;
                document.getElementById('edit_dob').value = n.dob;
            });
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