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
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trains as $train)
                        <tr>
                            <td>{{ $train->train_id }}</td>
                            <td>{{ $train->train_name }}</td>
                            <td>{{ $train->total_seats }}</td>
                            <td>
                                <span class="badge 
                                    @if($train->train_type === 'Express') bg-success
                                    @elseif($train->train_type === 'Intercity') bg-warning
                                    @elseif($train->train_type === 'Local') bg-danger
                                    @else bg-secondary
                                    @endif">
                                    {{ $train->train_type }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-warning edit-train-btn" data-bs-toggle="modal"
                                        data-bs-target="#editTrainModal" data-train-id="{{ $train->train_id }}">Edit</button>
                                    
                                    <form action="{{ route('admin.trains.destroy', $train->train_id) }}" method="POST" style="display:inline;"
                                        onsubmit="return confirm('Are you sure you want to delete {{ $train->train_name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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
            @if($trains->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $trains->links('pagination.bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add Train Modal -->
    <div class="modal fade" id="addTrainModal" tabindex="-1" aria-labelledby="addTrainModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.trains.store') }}" method="POST">
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
                            <label for="train_total_seats" class="form-label">Total Seats</label>
                            <input type="number" class="form-control" id="train_total_seats" name="total_seats" required min="1">
                        </div>
                        <div class="mb-3">
                            <label for="train_type" class="form-label">Type</label>
                            <select class="form-select" id="train_type" name="train_type" required>
                                <option value="Intercity" selected>Intercity</option>
                                <option value="Express">Express</option>
                                <option value="Local">Local</option>
                            </select>
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
                            <label for="edit_train_total_seats" class="form-label">Total Seats</label>
                            <input type="number" class="form-control" id="edit_train_total_seats" name="total_seats" required min="1">
                        </div>
                        <div class="mb-3">
                            <label for="edit_train_type" class="form-label">Type</label>
                            <select class="form-select" id="edit_train_type" name="train_type" required>
                                <option value="Intercity">Intercity</option>
                                <option value="Express">Express</option>
                                <option value="Local">Local</option>
                            </select>
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

        // Edit train functionality
        document.querySelectorAll('.edit-train-btn').forEach(button => {
            button.addEventListener('click', function() {
                const trainId = this.getAttribute('data-train-id');
                
                // Fetch train data
                fetch(`/adminpanel/trains/${trainId}/edit`)
                    .then(response => response.json())
                    .then(train => {
                        // Populate form fields
                        document.getElementById('edit_train_name').value = train.train_name;
                        document.getElementById('edit_train_total_seats').value = train.total_seats;
                        document.getElementById('edit_train_type').value = train.train_type;
                        
                        // Update form action
                        document.getElementById('editTrainForm').action = `/adminpanel/trains/${trainId}`;
                    })
                    .catch(error => {
                        console.error('Error fetching train data:', error);
                        alert('Error loading train data');
                    });
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