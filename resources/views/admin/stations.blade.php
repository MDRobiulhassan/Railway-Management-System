<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Train Stations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/stations.css') }}">
</head>

<body>
    <x-navbar />

    <div class="container">
        <h1>Train Station Management</h1>

        <!-- Success & error messages -->
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Action completed successfully
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <div class="mb-3 d-flex justify-content-between align-items-center">
            <input type="text" class="form-control w-25" id="searchInput" placeholder="Search stations...">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStationModal">
                <i class="fa-solid fa-plus"></i> Add Station
            </button>
        </div>

        <div class="table-container">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Station ID</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Hardcoded Stations -->
                    <tr>
                        <td>1</td>
                        <td>Dhaka Central</td>
                        <td><span class="badge bg-primary">DHK</span></td>
                        <td>Dhaka</td>
                        <td>Dhaka</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-station-btn" data-bs-toggle="modal"
                                    data-bs-target="#editStationModal" data-station="1">Edit</button>
                                <button class="btn btn-sm btn-danger" 
                                    title="Cannot delete main station">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Chittagong Junction</td>
                        <td><span class="badge bg-primary">CTG</span></td>
                        <td>Chittagong</td>
                        <td>Chittagong</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-station-btn" data-bs-toggle="modal"
                                    data-bs-target="#editStationModal" data-station="2">Edit</button>
                                <button class="btn btn-sm btn-danger"
                                    onclick="if(confirm('Are you sure you want to delete Chittagong Junction?')){alert('Deleted!')}">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Rajshahi Terminal</td>
                        <td><span class="badge bg-primary">RJH</span></td>
                        <td>Rajshahi</td>
                        <td>Rajshahi</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-station-btn" data-bs-toggle="modal"
                                    data-bs-target="#editStationModal" data-station="3">Edit</button>
                                <button class="btn btn-sm btn-danger"
                                    onclick="if(confirm('Are you sure you want to delete Rajshahi Terminal?')){alert('Deleted!')}">Delete</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Station Modal -->
    <div class="modal fade" id="addStationModal" tabindex="-1" aria-labelledby="addStationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStationModalLabel">Add New Station</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="station_name" class="form-label">Station Name</label>
                            <input type="text" class="form-control" id="station_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="station_code" class="form-label">Station Code</label>
                            <input type="text" class="form-control" id="station_code" maxlength="10" required>
                        </div>
                        <div class="mb-3">
                            <label for="station_city" class="form-label">City</label>
                            <input type="text" class="form-control" id="station_city" required>
                        </div>
                        <div class="mb-3">
                            <label for="station_state" class="form-label">State</label>
                            <input type="text" class="form-control" id="station_state" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add Station</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Station Modal -->
    <div class="modal fade" id="editStationModal" tabindex="-1" aria-labelledby="editStationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editStationForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStationModalLabel">Edit Station</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_station_name" class="form-label">Station Name</label>
                            <input type="text" class="form-control" id="edit_station_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_station_code" class="form-label">Station Code</label>
                            <input type="text" class="form-control" id="edit_station_code" maxlength="10" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_station_city" class="form-label">City</label>
                            <input type="text" class="form-control" id="edit_station_city" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_station_state" class="form-label">State</label>
                            <input type="text" class="form-control" id="edit_station_state" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Update Station</button>
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

        // Edit station functionality (hardcoded example)
        document.querySelectorAll('.edit-station-btn').forEach(button => {
            button.addEventListener('click', function () {
                const stationId = this.getAttribute('data-station');
                if (stationId == 2) { // Chittagong
                    document.getElementById('edit_station_name').value = 'Chittagong Junction';
                    document.getElementById('edit_station_code').value = 'CTG';
                    document.getElementById('edit_station_city').value = 'Chittagong';
                    document.getElementById('edit_station_state').value = 'Chittagong';
                } else if (stationId == 3) { // Rajshahi
                    document.getElementById('edit_station_name').value = 'Rajshahi Terminal';
                    document.getElementById('edit_station_code').value = 'RJH';
                    document.getElementById('edit_station_city').value = 'Rajshahi';
                    document.getElementById('edit_station_state').value = 'Rajshahi';
                }
            });
        });

        // Clear form when modals are closed
        ['addStationModal', 'editStationModal'].forEach(id => {
            document.getElementById(id).addEventListener('hidden.bs.modal', function () {
                this.querySelector('form').reset();
            });
        });

        // Auto-uppercase station codes
        document.querySelectorAll('input[id$="code"]').forEach(input => {
            input.addEventListener('input', function () {
                this.value = this.value.toUpperCase();
            });
        });
    </script>
</body>

</html>