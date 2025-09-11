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
                        <th>Train Name</th>
                        <th>Compartment Name</th>
                        <th>Class Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="compartmentTableBody">
                    @forelse($compartments as $compartment)
                        <tr>
                            <td>{{ $compartment->compartment_id }}</td>
                            <td>{{ $compartment->train->train_name ?? 'N/A' }}</td>
                            <td>{{ $compartment->compartment_name }}</td>
                            <td>{{ $compartment->class_name }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-warning edit-compartment-btn" data-bs-toggle="modal"
                                        data-bs-target="#editCompartmentModal" data-id="{{ $compartment->compartment_id }}">Edit</button>
                                    <form action="{{ route('admin.compartments.destroy', $compartment->compartment_id) }}" method="POST" style="display:inline;"
                                        onsubmit="return confirm('Delete this compartment?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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
                            <label for="add_train_id" class="form-label">Train</label>
                            <select class="form-select" id="add_train_id" name="train_id" required>
                                <option value="">Select Train...</option>
                                @foreach($trains as $train)
                                    <option value="{{ $train->train_id }}">{{ $train->train_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="add_compartment_name" class="form-label">Compartment</label>
                            <select class="form-select" id="add_compartment_name" name="compartment_name" required>
                                <option value="Ka">Ka</option>
                                <option value="Kha">Kha</option>
                                <option value="Ga">Ga</option>
                                <option value="Gha">Gha</option>
                                <option value="Uma">Uma</option>
                                <option value="Cha">Cha</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="add_class_name" class="form-label">Class Name</label>
                            <input type="text" class="form-control" id="add_class_name" name="class_name" readonly>
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
                            <label for="edit_train_id" class="form-label">Train</label>
                            <select class="form-select" id="edit_train_id" name="train_id" required>
                                @foreach($trains as $train)
                                    <option value="{{ $train->train_id }}">{{ $train->train_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_compartment_name" class="form-label">Compartment</label>
                            <select class="form-select" id="edit_compartment_name" name="compartment_name" required>
                                <option value="Ka">Ka</option>
                                <option value="Kha">Kha</option>
                                <option value="Ga">Ga</option>
                                <option value="Gha">Gha</option>
                                <option value="Uma">Uma</option>
                                <option value="Cha">Cha</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_class_name" class="form-label">Class Name</label>
                            <input type="text" class="form-control" id="edit_class_name" name="class_name" readonly>
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
        const compartmentMap = { 'Ka': 'AC', 'Kha': 'AC', 'Ga': 'Snigdha', 'Gha': 'Snigdha', 'Uma': 'Shovan', 'Cha': 'Shovan' };

        const addCompartmentName = document.getElementById('add_compartment_name');
        const addClassName = document.getElementById('add_class_name');
        const editCompartmentName = document.getElementById('edit_compartment_name');
        const editClassName = document.getElementById('edit_class_name');

        // Auto-set class based on compartment
        addCompartmentName.addEventListener('change', () => {
            addClassName.value = compartmentMap[addCompartmentName.value];
        });

        editCompartmentName.addEventListener('change', () => {
            editClassName.value = compartmentMap[editCompartmentName.value];
        });

        // Set initial class on page load
        addClassName.value = compartmentMap[addCompartmentName.value];

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const term = this.value.toLowerCase();
            tableBody.querySelectorAll('tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        // Add form posts to server; no JS override needed

        // Edit compartment
        tableBody.addEventListener('click', function (e) {
            if (e.target.classList.contains('edit-compartment-btn')) {
                const btn = e.target;
                const id = btn.getAttribute('data-id');
                fetch(`/adminpanel/compartments/${id}/edit`)
                    .then(r => r.json())
                    .then(c => {
                        document.getElementById('edit_compartment_id').value = c.compartment_id;
                        document.getElementById('edit_train_id').value = c.train_id;
                        editCompartmentName.value = c.compartment_name;
                        editClassName.value = c.class_name;
                        document.getElementById('editCompartmentForm').action = `/adminpanel/compartments/${id}`;
                    })
                    .catch(() => alert('Failed to load compartment'));
            } else if (e.target.classList.contains('delete-compartment-btn')) {
                if (confirm('Are you sure you want to delete this compartment?')) {
                    // handled by form submit; keep for legacy buttons
                }
            }
        });

        // Edit form posts to server
    </script>
</body>

</html>