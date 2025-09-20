<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/schedule.css') }}">
</head>

<body>
    <x-navbar />

    <div class="container">
        <h1>Schedule Management</h1>

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
                        <th>Train ID</th>
                        <th>Train</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Departure Time</th>
                        <th>Arrival Time</th>
                        <th>Duration (min)</th>
                        <th>Status</th>
                        <th>Ticket Prices</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="trainTableBody">
                    @foreach($schedules as $schedule)
                        <tr data-id="{{ $schedule->schedule_id }}">
                            <td>{{ $schedule->schedule_id }}</td>
                            <td><strong>{{ $schedule->train->train_name }}</strong></td>
                            <td>{{ $schedule->sourceStation->name }}</td>
                            <td>{{ $schedule->destinationStation->name }}</td>
                            <td>{{ $schedule->departure_time->format('Y-m-d H:i') }}</td>
                            <td>{{ $schedule->arrival_time->format('Y-m-d H:i') }}</td>
                            <td>{{ $schedule->duration }}</td>
                            <td>
                                <span class="badge bg-success">
                                    Scheduled
                                </span>
                            </td>
                            <td>
                                @php
                                    $prices = $schedule->train->compartments->groupBy('class_name')->map(function ($compartments) {
                                        return $compartments->first()->ticketPrices->first()?->base_price ?? 0;
                                    });
                                @endphp
                                <div><strong>Snigdha:</strong> {{ $prices->get('Snigdha', 0) }} BDT</div>
                                <div><strong>Shovan:</strong> {{ $prices->get('Shovan', 0) }} BDT</div>
                                <div><strong>AC:</strong> {{ $prices->get('AC', 0) }} BDT</div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-warning edit-train-btn"
                                        data-id="{{ $schedule->schedule_id }}">Edit</button>
                                    <button class="btn btn-sm btn-danger delete-train-btn"
                                        data-id="{{ $schedule->schedule_id }}">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($schedules->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $schedules->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add Train Modal -->
    <div class="modal fade" id="addTrainModal" tabindex="-1" aria-labelledby="addTrainModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addTrainForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTrainModalLabel">Add New Train</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Train & Status Row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Train</label>
                                    <select class="form-select" id="train_id" required>
                                        <option value="">Select Train</option>
                                        @foreach($trains as $train)
                                            <option value="{{ $train->train_id }}">{{ $train->train_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" id="status" required>
                                        <option value="scheduled">Scheduled</option>
                                        <option value="delayed">Delayed</option>
                                        <option value="cancelled">Cancelled</option>
                                        <option value="arrived">Arrived</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Departure & Arrival Stations Row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Departure Station</label>
                                    <select class="form-select" id="source_station_id" required>
                                        <option value="">Select Departure Station</option>
                                        @foreach($stations as $station)
                                            <option value="{{ $station->station_id }}">{{ $station->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Arrival Station</label>
                                    <select class="form-select" id="destination_station_id" required>
                                        <option value="">Select Arrival Station</option>
                                        @foreach($stations as $station)
                                            <option value="{{ $station->station_id }}">{{ $station->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Departure & Arrival Times Row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Departure Time</label>
                                    <input type="datetime-local" class="form-control" id="departure_time" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Arrival Time</label>
                                    <input type="datetime-local" class="form-control" id="arrival_time" required>
                                </div>
                            </div>
                        </div>

                        <!-- Duration (Auto-calculated) -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Duration (minutes)</label>
                                <input type="number" class="form-control" id="duration_minutes" min="1" required readonly>
                            </div>
                        </div>

                        <!-- Ticket Pricing -->
                        <div class="row mt-3">

                            <div class="col-md-4">
                                <label class="form-label">AC Price</label>
                                <input type="number" class="form-control" id="ac_price" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Shovan Class Price</label>
                                <input type="number" class="form-control" id="shovan_price" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Snigdha Price</label>
                                <input type="number" class="form-control" id="snigdha_price" min="0" required>
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
                <form id="editTrainForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTrainModalLabel">Edit Train</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_schedule_id">

                        <!-- Train & Status Row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Train</label>
                                    <select class="form-select" id="edit_train_id" required>
                                        <option value="">Select Train</option>
                                        @foreach($trains as $train)
                                            <option value="{{ $train->train_id }}">{{ $train->train_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" id="edit_status" required>
                                        <option value="scheduled">Scheduled</option>
                                        <option value="delayed">Delayed</option>
                                        <option value="cancelled">Cancelled</option>
                                        <option value="arrived">Arrived</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Departure & Arrival Stations Row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Departure Station</label>
                                    <select class="form-select" id="edit_source_station_id" required>
                                        <option value="">Select Departure Station</option>
                                        @foreach($stations as $station)
                                            <option value="{{ $station->station_id }}">{{ $station->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Arrival Station</label>
                                    <select class="form-select" id="edit_destination_station_id" required>
                                        <option value="">Select Arrival Station</option>
                                        @foreach($stations as $station)
                                            <option value="{{ $station->station_id }}">{{ $station->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Departure & Arrival Times Row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Departure Time</label>
                                    <input type="datetime-local" class="form-control" id="edit_departure_time" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Arrival Time</label>
                                    <input type="datetime-local" class="form-control" id="edit_arrival_time" required>
                                </div>
                            </div>
                        </div>

                        <!-- Duration (Auto-calculated) -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Duration (minutes)</label>
                                <input type="number" class="form-control" id="edit_duration_minutes" min="1" required readonly>
                            </div>
                        </div>


                        <!-- Ticket Pricing -->
                        <div class="row mt-3">

                            <div class="col-md-4">
                                <label class="form-label">AC Price</label>
                                <input type="number" class="form-control" id="edit_ac_price" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Shovan Class Price</label>
                                <input type="number" class="form-control" id="edit_shovan_price" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Snigdha Price</label>
                                <input type="number" class="form-control" id="edit_snigdha_price" min="0" required>
                            </div>
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

        // Auto-calculate duration
        function calculateDuration() {
            const departureTime = document.getElementById('departure_time').value;
            const arrivalTime = document.getElementById('arrival_time').value;
            
            if (departureTime && arrivalTime) {
                const departure = new Date(departureTime);
                const arrival = new Date(arrivalTime);
                const durationMinutes = Math.round((arrival - departure) / (1000 * 60));
                
                if (durationMinutes > 0) {
                    document.getElementById('duration_minutes').value = durationMinutes;
                }
            }
        }

        function calculateEditDuration() {
            const departureTime = document.getElementById('edit_departure_time').value;
            const arrivalTime = document.getElementById('edit_arrival_time').value;
            
            if (departureTime && arrivalTime) {
                const departure = new Date(departureTime);
                const arrival = new Date(arrivalTime);
                const durationMinutes = Math.round((arrival - departure) / (1000 * 60));
                
                if (durationMinutes > 0) {
                    document.getElementById('edit_duration_minutes').value = durationMinutes;
                }
            }
        }

        // Add event listeners for auto-calculation
        document.getElementById('departure_time').addEventListener('change', calculateDuration);
        document.getElementById('arrival_time').addEventListener('change', calculateDuration);
        document.getElementById('edit_departure_time').addEventListener('change', calculateEditDuration);
        document.getElementById('edit_arrival_time').addEventListener('change', calculateEditDuration);

        // Add Train
        addForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('train_id', document.getElementById('train_id').value);
            formData.append('source_station_id', document.getElementById('source_station_id').value);
            formData.append('destination_station_id', document.getElementById('destination_station_id').value);
            formData.append('departure_time', document.getElementById('departure_time').value);
            formData.append('arrival_time', document.getElementById('arrival_time').value);
            formData.append('duration_minutes', document.getElementById('duration_minutes').value);
            formData.append('status', document.getElementById('status').value);
            formData.append('ac_price', document.getElementById('ac_price').value);
            formData.append('shovan_price', document.getElementById('shovan_price').value);
            formData.append('snigdha_price', document.getElementById('snigdha_price').value);

            fetch('/admin/schedules', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error creating schedule: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error creating schedule: ' + error.message);
                });
        });

        // Edit Train
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('edit-train-btn')) {
                const id = e.target.dataset.id;

                fetch(`/admin/schedules/${id}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Schedule data:', data); // Debug log
                        
                        document.getElementById('edit_schedule_id').value = data.schedule_id;
                        
                        // Set train dropdown
                        const trainSelect = document.getElementById('edit_train_id');
                        trainSelect.value = data.train_id;
                        
                        // Set station dropdowns
                        const sourceSelect = document.getElementById('edit_source_station_id');
                        const destSelect = document.getElementById('edit_destination_station_id');
                        sourceSelect.value = data.source_station_id;
                        destSelect.value = data.destination_station_id;
                        
                        // Set datetime inputs
                        document.getElementById('edit_departure_time').value = data.departure_time;
                        document.getElementById('edit_arrival_time').value = data.arrival_time;
                        
                        // Set other fields
                        document.getElementById('edit_duration_minutes').value = data.duration_minutes;
                        document.getElementById('edit_status').value = data.status;
                        
                        // Set price fields
                        document.getElementById('edit_ac_price').value = data.prices.AC || 0;
                        document.getElementById('edit_shovan_price').value = data.prices.Shovan || 0;
                        document.getElementById('edit_snigdha_price').value = data.prices.Snigdha || 0;
                        
                        new bootstrap.Modal(document.getElementById('editTrainModal')).show();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error loading schedule data: ' + error.message);
                    });
            }
            if (e.target.classList.contains('delete-train-btn')) {
                if (confirm('Are you sure you want to delete this schedule?')) {
                    const id = e.target.dataset.id;

                    fetch(`/admin/schedules/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Error deleting schedule: ' + (data.message || 'Unknown error'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error deleting schedule: ' + error.message);
                        });
                }
            }
        });

        editForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const id = document.getElementById('edit_schedule_id').value;
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('_method', 'PUT');
            formData.append('train_id', document.getElementById('edit_train_id').value);
            formData.append('source_station_id', document.getElementById('edit_source_station_id').value);
            formData.append('destination_station_id', document.getElementById('edit_destination_station_id').value);
            formData.append('departure_time', document.getElementById('edit_departure_time').value);
            formData.append('arrival_time', document.getElementById('edit_arrival_time').value);
            formData.append('duration_minutes', document.getElementById('edit_duration_minutes').value);
            formData.append('status', document.getElementById('edit_status').value);
            formData.append('ac_price', document.getElementById('edit_ac_price').value);
            formData.append('shovan_price', document.getElementById('edit_shovan_price').value);
            formData.append('snigdha_price', document.getElementById('edit_snigdha_price').value);

            fetch(`/admin/schedules/${id}`, {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error updating schedule: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating schedule: ' + error.message);
                });
        });
    </script>
</body>

</html>