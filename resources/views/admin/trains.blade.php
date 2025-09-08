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
                    <tr>
                        <td>1</td>
                        <td>Express 101</td>
                        <td>300</td>
                        <td><span class="badge bg-success">Express</span></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-train-btn" data-bs-toggle="modal"
                                    data-bs-target="#editTrainModal" data-train="1">Edit</button>
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this train?')">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>InterCity 202</td>
                        <td>250</td>
                        <td><span class="badge bg-warning">Intercity</span></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-train-btn" data-bs-toggle="modal"
                                    data-bs-target="#editTrainModal" data-train="2">Edit</button>
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this train?')">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Local 303</td>
                        <td>150</td>
                        <td><span class="badge bg-danger">Local</span></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-train-btn" data-bs-toggle="modal"
                                    data-bs-target="#editTrainModal" data-train="3">Edit</button>
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this train?')">Delete</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Train Modal -->
    <div class="modal fade" id="addTrainModal" tabindex="-1" aria-labelledby="addTrainModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTrainModalLabel">Add New Train</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="train_name" class="form-label">Train Name</label>
                            <input type="text" class="form-control" id="train_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="train_total_seats" class="form-label">Total Seats</label>
                            <input type="number" class="form-control" id="train_total_seats" required min="1">
                        </div>
                        <div class="mb-3">
                            <label for="train_status" class="form-label">Type</label>
                            <select class="form-select" id="train_status" required>
                                <option value="active" selected>Intercity</option>
                                <option value="maintenance">Express</option>
                                <option value="retired">Local</option>
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
                <form id="editTrainForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTrainModalLabel">Edit Train</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_train_name" class="form-label">Train Name</label>
                            <input type="text" class="form-control" id="edit_train_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_train_total_seats" class="form-label">Total Seats</label>
                            <input type="number" class="form-control" id="edit_train_total_seats" required min="1">
                        </div>
                        <div class="mb-3">
                            <label for="edit_train_status" class="form-label">Type</label>
                            <select class="form-select" id="edit_train_status" required>
                                <option value="active">Intercity</option>
                                <option value="maintenance">Express</option>
                                <option value="retired">Local</option>
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

        // Edit train functionality (simulated fetch)
        const trainData = {
            1: { name: 'Express 101', total_seats: 300, status: 'Express' },
            2: { name: 'InterCity 202', total_seats: 250, status: 'Intercity' },
            3: { name: 'Local 303', total_seats: 150, status: 'Local' }
        };

        document.querySelectorAll('.edit-train-btn').forEach(button => {
            button.addEventListener('click', function () {
                const trainId = this.getAttribute('data-train');
                const train = trainData[trainId];

                document.getElementById('editTrainForm').action = `/trains/${trainId}`;
                document.getElementById('edit_train_name').value = train.name;
                document.getElementById('edit_train_total_seats').value = train.total_seats;
                document.getElementById('edit_train_status').value = train.status;
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