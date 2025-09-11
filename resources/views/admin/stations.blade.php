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
            <input type="text" class="form-control w-25" id="searchInput" placeholder="Search stations...">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#stationModal">
                <i class="fa-solid fa-plus"></i> Add Station
            </button>
        </div>

        <div class="table-container">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Station ID</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="stationTableBody">
                    @forelse($stations as $station)
                        <tr>
                            <td>{{ $station->station_id }}</td>
                            <td>{{ $station->name }}</td>
                            <td>{{ $station->location }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-warning edit-station-btn" data-bs-toggle="modal"
                                        data-bs-target="#stationModal" data-station-id="{{ $station->station_id }}">Edit</button>
                                    <form action="{{ route('admin.stations.destroy', $station->station_id) }}" method="POST" style="display:inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this station?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No stations found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Station Modal -->
    <div class="modal fade" id="stationModal" tabindex="-1" aria-labelledby="stationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="stationForm" method="POST">
                    @csrf
                    <div id="method-field"></div>
                    <div class="modal-header">
                        <h5 class="modal-title" id="stationModalLabel">Add New Station</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="station_name" class="form-label">Station Name</label>
                            <input type="text" class="form-control" id="station_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="station_location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="station_location" name="location" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <script>
        const stationForm = document.getElementById('stationForm');
        const methodField = document.getElementById('method-field');
        const modalTitle = document.getElementById('stationModalLabel');

        // Default to create
        document.addEventListener('DOMContentLoaded', function () {
            stationForm.action = '/adminpanel/stations';
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#stationTableBody tr');
            tableRows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(searchTerm) ? '' : 'none';
            });
        });

        // Edit station functionality
        document.querySelectorAll('.edit-station-btn').forEach(button => {
            button.addEventListener('click', function () {
                const stationId = this.getAttribute('data-station-id');
                modalTitle.textContent = 'Edit Station';
                methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';

                fetch(`/adminpanel/stations/${stationId}/edit`)
                    .then(response => response.json())
                    .then(station => {
                        document.getElementById('station_name').value = station.name;
                        document.getElementById('station_location').value = station.location;
                        stationForm.action = `/adminpanel/stations/${stationId}`;
                    })
                    .catch(err => {
                        console.error('Error loading station:', err);
                        alert('Failed to load station data');
                    });
            });
        });

        // Reset to Add mode when modal hides
        document.getElementById('stationModal').addEventListener('hidden.bs.modal', function () {
            modalTitle.textContent = 'Add New Station';
            methodField.innerHTML = '';
            stationForm.action = '/adminpanel/stations';
            stationForm.reset();
        });
    </script>
</body>

</html>