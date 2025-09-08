<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Trains</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/trains.css') }}">
</head>

<body>
    <x-navbar />

    <div class="container">
        <h1>Train Management</h1>

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
            <input type="text" class="form-control w-25" id="searchInput" placeholder="Search trains...">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTrainModal">
                <i class="fa-solid fa-plus"></i> Add Train
            </button>
        </div>

        <div class="table-container">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Train ID</th>
                        <th>Name</th>
                        <th>Total Seats</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trains as $t)
                    <tr>
                        <td>{{ $t->train_id }}</td>
                        <td>{{ $t->train_name }}</td>
                        <td>{{ $t->total_seats }}</td>
                        <td>
                            @php
                                $status = strtolower($t->train_type ?? '');
                                $cls = $status === 'active' ? 'success' : ($status === 'maintenance' ? 'warning' : ($status === 'retired' ? 'danger' : 'secondary'));
                            @endphp
                            <span class="badge bg-{{ $cls }}">{{ ucfirst($status ?: 'Unknown') }}</span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-train-btn" data-bs-toggle="modal" data-bs-target="#editTrainModal" data-train='@json($t)'>Edit</button>
                                <form action="{{ route('admin.trains.destroy', $t) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this train?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No trains found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @isset($trains)
        <div class="mt-3">{{ $trains->links() }}</div>
        @endisset
    </div>

    <!-- Add Train Modal -->
    <div class="modal fade" id="addTrainModal" tabindex="-1" aria-labelledby="addTrainModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.trains.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTrainModalLabel">Add New Train</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="train_name" class="form-label">Train Name</label>
                            <input type="text" class="form-control" id="train_name" name="train_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="train_status" class="form-label">Status</label>
                            <select class="form-select" id="train_status" name="train_type" required>
                                <option value="active">Active</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="retired">Retired</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="train_total_seats" class="form-label">Total Seats</label>
                            <input type="number" class="form-control" id="train_total_seats" name="total_seats" required min="1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add Train</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Train Modal -->
    <div class="modal fade" id="editTrainModal" tabindex="-1" aria-labelledby="editTrainModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editTrainForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTrainModalLabel">Edit Train</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_train_name" class="form-label">Train Name</label>
                            <input type="text" class="form-control" id="edit_train_name" name="train_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_train_status" class="form-label">Status</label>
                            <select class="form-select" id="edit_train_status" name="train_type" required>
                                <option value="active">Active</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="retired">Retired</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_train_total_seats" class="form-label">Total Seats</label>
                            <input type="number" class="form-control" id="edit_train_total_seats" name="total_seats" required min="1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Update Train</button>
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
                row.style.display = row.textContent.toLowerCase().includes(searchTerm) ? '' : 'none';
            });
        });

        // Edit train populate
        document.querySelectorAll('.edit-train-btn').forEach(button => {
            button.addEventListener('click', function () {
                const train = JSON.parse(this.getAttribute('data-train'));
                document.getElementById('editTrainForm').action = `/adminpanel/trains/${train.train_id}`;
                document.getElementById('edit_train_name').value = train.train_name;
                document.getElementById('edit_train_status').value = (train.train_type || '').toLowerCase();
                document.getElementById('edit_train_total_seats').value = train.total_seats;
            });
        });

        // Clear forms when modals are closed
        document.getElementById('addTrainModal').addEventListener('hidden.bs.modal', function () {
            this.querySelector('form').reset();
        });
        document.getElementById('editTrainModal').addEventListener('hidden.bs.modal', function () {
            this.querySelector('form').reset();
        });
    </script>
</body>

</html>