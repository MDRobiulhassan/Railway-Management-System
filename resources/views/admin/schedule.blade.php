<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/schedule.css') }}">
</head>

<body>
    <x-navbar />

    <div class="container">
        <h1>Schedule Management</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
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
            <table class="table table-striped" id="trainTable">
                <thead class="table-dark">
                    <tr>
                        <th>Schedule ID</th>
                        <th>Train</th>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Duration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="trainTableBody">
                    @forelse($schedules as $s)
                        <tr data-id="{{ $s->schedule_id }}">
                            <td>{{ $s->schedule_id }}</td>
                            <td><strong>{{ $s->train->train_name ?? 'N/A' }}</strong></td>
                            <td>{{ $s->sourceStation->name ?? 'N/A' }}</td>
                            <td>{{ $s->destinationStation->name ?? 'N/A' }}</td>
                            <td>{{ $s->departure_time ? $s->departure_time->format('Y-m-d H:i') : '-' }}</td>
                            <td>{{ $s->arrival_time ? $s->arrival_time->format('Y-m-d H:i') : '-' }}</td>
                            <td>{{ $s->formatted_duration ?? '' }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-warning edit-train-btn" data-id="{{ $s->schedule_id }}">Edit</button>
                                    <form action="{{ route('admin.schedule.destroy', $s->schedule_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this schedule?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">No schedules found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Train Modal -->
    <div class="modal fade" id="addTrainModal" tabindex="-1" aria-labelledby="addTrainModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addTrainForm" method="POST" action="{{ route('admin.schedule.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTrainModalLabel">Add New Train</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Train Dropdown -->
                                <div class="mb-3">
                                    <label class="form-label">Train</label>
                                    <select class="form-select" id="train_id" name="train_id" required>
                                        <option value="">Select Train...</option>
                                        @foreach($trains as $t)
                                            <option value="{{ $t->train_id }}">{{ $t->train_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Departure Dropdown -->
                                <div class="mb-3">
                                    <label class="form-label">Departure Station</label>
                                    <select class="form-select" id="source_id" name="source_id" required>
                                        <option value="">Select Station...</option>
                                        @foreach($stations as $st)
                                            <option value="{{ $st->station_id }}">{{ $st->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Arrival Dropdown -->
                                <div class="mb-3">
                                    <label class="form-label">Arrival Station</label>
                                    <select class="form-select" id="destination_id" name="destination_id" required>
                                        <option value="">Select Station...</option>
                                        @foreach($stations as $st)
                                            <option value="{{ $st->station_id }}">{{ $st->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Departure Time</label>
                                    <input type="datetime-local" class="form-control" id="departure_time" name="departure_time" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Arrival Time</label>
                                    <input type="datetime-local" class="form-control" id="arrival_time" name="arrival_time" required>
                                </div>
                            </div>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editTrainForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTrainModalLabel">Edit Train</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_train_id">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Train Dropdown -->
                                <div class="mb-3">
                                    <label class="form-label">Train</label>
                                    <select class="form-select" id="edit_train_id" name="train_id" required>
                                        @foreach($trains as $t)
                                            <option value="{{ $t->train_id }}">{{ $t->train_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Departure Dropdown -->
                                <div class="mb-3">
                                    <label class="form-label">Departure Station</label>
                                    <select class="form-select" id="edit_source_id" name="source_id" required>
                                        @foreach($stations as $st)
                                            <option value="{{ $st->station_id }}">{{ $st->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Arrival Dropdown -->
                                <div class="mb-3">
                                    <label class="form-label">Arrival Station</label>
                                    <select class="form-select" id="edit_destination_id" name="destination_id" required>
                                        @foreach($stations as $st)
                                            <option value="{{ $st->station_id }}">{{ $st->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Departure Time</label>
                                    <input type="datetime-local" class="form-control" id="edit_departure_time" name="departure_time" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Arrival Time</label>
                                    <input type="datetime-local" class="form-control" id="edit_arrival_time" name="arrival_time" required>
                                </div>
                            </div>
                        </div>

                        <!-- No pricing here; pricing managed in Ticket Prices page -->
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Update Train</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script>
        const tableBody = document.getElementById('trainTableBody');
        const addForm = document.getElementById('addTrainForm');
        const editForm = document.getElementById('editTrainForm');

        // Search
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const term = this.value.toLowerCase();
            document.querySelectorAll('#trainTableBody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        // Add posts to server directly

        // Edit Train - load JSON and set form action
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('edit-train-btn')) {
                const id = e.target.dataset.id;
                fetch(`/adminpanel/schedule/${id}/edit`)
                    .then(r => r.json())
                    .then(s => {
                        document.getElementById('edit_train_id').value = id;
                        document.getElementById('edit_train_id').value = s.train_id;
                        document.getElementById('edit_source_id').value = s.source_id;
                        document.getElementById('edit_destination_id').value = s.destination_id;
                        document.getElementById('edit_departure_time').value = s.departure_time;
                        document.getElementById('edit_arrival_time').value = s.arrival_time;
                        editForm.action = `/adminpanel/schedule/${id}`;
                        new bootstrap.Modal(document.getElementById('editTrainModal')).show();
                    })
                    .catch(() => alert('Failed to load schedule'));
            }
        });

        // Edit form posts to server
    </script>
</body>

</html>