<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Trains</title>
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
            <table class="table table-striped" id="trainTable">
                <thead class="table-dark">
                    <tr>
                        <th>Schedule ID</th>
                        <th>Train</th>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>Departure Time</th>
                        <th>Arrival Time</th>
                        <th>Duration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="trainTableBody">
                    @forelse($schedules as $s)
                    <tr>
                        <td>{{ $s->schedule_id }}</td>
                        <td>#{{ $s->train_id }} - {{ $s->train->train_name ?? '' }}</td>
                        <td>#{{ $s->source_id }} - {{ $s->sourceStation->name ?? '' }}</td>
                        <td>#{{ $s->destination_id }} - {{ $s->destinationStation->name ?? '' }}</td>
                        <td>{{ optional($s->departure_time)->format('Y-m-d H:i') }}</td>
                        <td>{{ optional($s->arrival_time)->format('Y-m-d H:i') }}</td>
                        <td>{{ $s->formatted_duration }}</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-train-btn" data-schedule='@json($s)'>Edit</button>
                                <form action="{{ route('admin.schedule.destroy', $s) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this schedule?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger delete-train-btn" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No schedules found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @isset($schedules)
        <div class="mt-3">{{ $schedules->links() }}</div>
        @endisset
    </div>

    <!-- Add Train Modal -->
    <div class="modal fade" id="addTrainModal" tabindex="-1" aria-labelledby="addTrainModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addTrainForm" method="POST" action="{{ route('admin.schedule.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTrainModalLabel">Add New Schedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Train ID</label>
                                    <input type="number" class="form-control" id="train_number" name="train_id" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Source Station ID</label>
                                    <input type="number" class="form-control" id="route" name="source_id" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Destination Station ID</label>
                                    <input type="number" class="form-control" id="departure" name="destination_id" required>
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
                        <button type="submit" class="btn btn-success">Add Schedule</button>
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
                        <h5 class="modal-title" id="editTrainModalLabel">Edit Schedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_train_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Train ID</label>
                                    <input type="number" class="form-control" id="edit_train_number" name="train_id" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Source Station ID</label>
                                    <input type="number" class="form-control" id="edit_route" name="source_id" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Destination Station ID</label>
                                    <input type="number" class="form-control" id="edit_departure" name="destination_id" required>
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

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label">Shovan Price</label>
                                <input type="number" class="form-control" id="edit_economy_price" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">AC Price</label>
                                <input type="number" class="form-control" id="edit_ac_price" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Snigdha Class Price</label>
                                <input type="number" class="form-control" id="edit_first_price" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Update Schedule</button>
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

        // Populate edit
        document.querySelectorAll('.edit-train-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const s = JSON.parse(this.getAttribute('data-schedule'));
                const form = document.getElementById('editTrainForm');
                form.action = `/adminpanel/schedule/${s.schedule_id}`;
                document.getElementById('edit_train_number').value = s.train_id;
                document.getElementById('edit_route').value = s.source_id;
                document.getElementById('edit_departure').value = s.destination_id;
                const pad = n => String(n).padStart(2,'0');
                const dt1 = new Date(s.departure_time);
                const dt2 = new Date(s.arrival_time);
                document.getElementById('edit_departure_time').value = `${dt1.getFullYear()}-${pad(dt1.getMonth()+1)}-${pad(dt1.getDate())}T${pad(dt1.getHours())}:${pad(dt1.getMinutes())}`;
                document.getElementById('edit_arrival_time').value = `${dt2.getFullYear()}-${pad(dt2.getMonth()+1)}-${pad(dt2.getDate())}T${pad(dt2.getHours())}:${pad(dt2.getMinutes())}`;
            });
        });
    </script>
</body>

</html>