<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Compartments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/compartments.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <x-navbar />

    <div class="container">
        <h1>Train Compartment Management</h1>

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
            <input type="text" id="searchInput" class="form-control w-25" placeholder="Search compartments...">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCompartmentModal">
                <i class="fa-solid fa-plus"></i> Add Compartment
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Compartment ID</th>
                        <th>Train ID</th>
                        <th>Compartment Name</th>
                        <th>Class Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="compartmentTableBody">
                    @forelse($compartments as $c)
                    <tr>
                        <td>{{ $c->compartment_id }}</td>
                        <td>#{{ $c->train_id }} - {{ $c->train->train_name ?? '' }}</td>
                        <td>{{ $c->compartment_name }}</td>
                        <td>{{ $c->class_name }}</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-compartment-btn" data-bs-toggle="modal" data-bs-target="#editCompartmentModal"
                                    data-compartment='@json($c)'>Edit</button>
                                <form action="{{ route('admin.compartments.update', $c) }}" method="POST" class="d-inline-block me-1" onsubmit="return false;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <form action="{{ route('admin.compartments.destroy', $c) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this compartment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger delete-compartment-btn">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No compartments found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @isset($compartments)
        <div class="mt-3">{{ $compartments->links() }}</div>
        @endisset
    </div>

    <!-- Add Compartment Modal -->
    <div class="modal fade" id="addCompartmentModal" tabindex="-1" aria-labelledby="addCompartmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addCompartmentForm" method="POST" action="{{ route('admin.compartments.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCompartmentModalLabel">Add Compartment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="add_train_id" class="form-label">Train ID</label>
                            <input type="number" class="form-control" id="add_train_id" name="train_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="add_compartment_name" class="form-label">Compartment Name</label>
                            <input type="text" class="form-control" id="add_compartment_name" name="compartment_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="add_class_name" class="form-label">Class Name</label>
                            <input type="text" class="form-control" id="add_class_name" name="class_name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add Compartment</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Compartment Modal -->
    <div class="modal fade" id="editCompartmentModal" tabindex="-1" aria-labelledby="editCompartmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editCompartmentForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCompartmentModalLabel">Edit Compartment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_compartment_id">
                        <div class="mb-3">
                            <label for="edit_train_id" class="form-label">Train ID</label>
                            <input type="number" class="form-control" id="edit_train_id" name="train_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_compartment_name" class="form-label">Compartment Name</label>
                            <input type="text" class="form-control" id="edit_compartment_name" name="compartment_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_class_name" class="form-label">Class Name</label>
                            <input type="text" class="form-control" id="edit_class_name" name="class_name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Update Compartment</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const tableBody = document.getElementById('compartmentTableBody');

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const term = this.value.toLowerCase();
            tableBody.querySelectorAll('tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });

        // Populate edit form with data
        document.querySelectorAll('.edit-compartment-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const c = JSON.parse(this.getAttribute('data-compartment'));
                document.getElementById('editCompartmentForm').action = `/adminpanel/compartments/${c.compartment_id}`;
                document.getElementById('edit_compartment_id').value = c.compartment_id;
                document.getElementById('edit_train_id').value = c.train_id;
                document.getElementById('edit_compartment_name').value = c.compartment_name;
                document.getElementById('edit_class_name').value = c.class_name;
            });
        });
    </script>
</body>

</html>