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
                    @forelse($stations as $s)
                    <tr>
                        <td>{{ $s->station_id }}</td>
                        <td>{{ $s->name }}</td>
                        <td><span class="badge bg-primary">{{ Str::upper(Str::substr($s->name,0,3)) }}</span></td>
                        <td>{{ $s->location }}</td>
                        <td>{{ $s->location }}</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-station-btn" data-bs-toggle="modal" data-bs-target="#editStationModal"
                                    data-station='@json($s)'>Edit</button>
                                <form action="{{ route('admin.stations.destroy', $s) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this station?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No stations found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @isset($stations)
        <div class="mt-3">
            {{ $stations->links() }}
        </div>
        @endisset
    </div>

    <!-- Add Station Modal -->
    <div class="modal fade" id="addStationModal" tabindex="-1" aria-labelledby="addStationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.stations.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStationModalLabel">Add New Station</h5>
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
                <form id="editStationForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStationModalLabel">Edit Station</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_station_name" class="form-label">Station Name</label>
                            <input type="text" class="form-control" id="edit_station_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_station_location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="edit_station_location" name="location" required>
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

        // Edit station functionality populate from data attribute
        document.querySelectorAll('.edit-station-btn').forEach(button => {
            button.addEventListener('click', function () {
                const station = JSON.parse(this.getAttribute('data-station'));
                document.getElementById('editStationForm').action = `/adminpanel/stations/${station.station_id}`;
                document.getElementById('edit_station_name').value = station.name || '';
                document.getElementById('edit_station_location').value = station.location || '';
            });
        });

        // Clear form when modals are closed
        ['addStationModal', 'editStationModal'].forEach(id => {
            document.getElementById(id).addEventListener('hidden.bs.modal', function () {
                this.querySelector('form').reset();
            });
        });

        // No code fields now
    </script>
</body>

</html>