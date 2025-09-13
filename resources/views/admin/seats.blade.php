<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Seats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/seats.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <x-navbar />
    <div class="container mt-4">
        <h1>Train Seat Management</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="mb-3 d-flex justify-content-between align-items-center">
            <input type="text" id="searchInput" class="form-control w-25" placeholder="Search seats...">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#seatModal">
                <i class="fa-solid fa-plus"></i> Add Seat
            </button>
        </div>

        @php
            $groupedSeats = $seats->groupBy(function($seat) {
                return $seat->compartment->train_id ?? 'unknown';
            });
        @endphp

        @forelse($groupedSeats as $trainId => $trainSeats)
            @php
                $firstSeat = $trainSeats->first();
                $trainName = $firstSeat->train->train_name ?? 'N/A';
                $seatCount = $trainSeats->count();
            @endphp
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        {{ $trainName }}
                        <span class="badge bg-primary ms-2">{{ $seatCount }} seats</span>
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Seat ID</th>
                                <th>Compartment</th>
                                <th>Class</th>
                                <th>Seat Number</th>
                                <th>Availability</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trainSeats as $s)
                                <tr>
                                    <td>{{ $s->seat_id }}</td>
                                    <td>{{ $s->compartment->compartment_name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge 
                                            @if(($s->compartment->class_name ?? '') === 'AC') bg-success
                                            @elseif(($s->compartment->class_name ?? '') === 'Snigdha') bg-warning
                                            @elseif(($s->compartment->class_name ?? '') === 'Shovan') bg-info
                                            @else bg-secondary
                                            @endif">
                                            {{ $s->compartment->class_name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>{{ $s->seat_number }}</td>
                                    <td>
                                        <span class="badge {{ $s->is_available ? 'bg-success' : 'bg-danger' }}">
                                            {{ $s->is_available ? 'Available' : 'Unavailable' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-warning edit-seat-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#seatModal" 
                                                data-id="{{ $s->seat_id }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.seats.destroy', $s->seat_id) }}" 
                                                method="POST" 
                                                style="display:inline;" 
                                                onsubmit="return confirm('Delete this seat?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="alert alert-info">No seats found</div>
        @endforelse

        <!-- Pagination -->
        @if($seats->hasPages())
            <div class="d-flex justify-content-center">
                <nav aria-label="Seats pagination">
                    {{ $seats->links('pagination::bootstrap-4') }}
                </nav>
            </div>
        @endif
    </div>

    <!-- Modal for Add/Edit Seat -->
    <div class="modal fade" id="seatModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Seat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="seatForm" method="POST">
                        @csrf
                        <div id="method-field"></div>
                        <div class="mb-3">
                            <label>Train</label>
                            <select class="form-select" id="train_id" name="train_id" required>
                                <option value="">Select Train...</option>
                                @foreach($trains as $t)
                                    <option value="{{ $t->train_id }}">{{ $t->train_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Compartment</label>
                            <select class="form-select" id="compartment_id" name="compartment_id" required>
                                <option value="">Select Compartment...</option>
                                @foreach($compartments as $c)
                                    <option value="{{ $c->compartment_id }}" data-class="{{ $c->class_name }}" data-train="{{ $c->train_id }}">{{ $c->compartment_name }} ({{ $c->class_name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Class Name</label>
                            <input type="text" class="form-control" id="class_name" readonly>
                        </div>
                        <div class="mb-3">
                            <label>Seat Number</label>
                            <input type="text" class="form-control" id="seat_number" name="seat_number" required>
                        </div>
                        <div class="mb-3">
                            <label>Availability</label>
                            <select class="form-select" id="is_available" name="is_available" required>
                                <option value="1">Available</option>
                                <option value="0">Unavailable</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const seatTableBody = document.getElementById('seatTableBody');
        const seatForm = document.getElementById('seatForm');
        const methodField = document.getElementById('method-field');
        const modalTitle = document.getElementById('modalTitle');
        const classInput = document.getElementById('class_name');
        const compartmentSelect = document.getElementById('compartment_id');
        const trainSelect = document.getElementById('train_id');

        // Default add action
        document.addEventListener('DOMContentLoaded', function () {
            seatForm.action = '/adminpanel/seats';
        });

        // Filter compartments by train and set class
        trainSelect.addEventListener('change', () => {
            const trainId = trainSelect.value;
            Array.from(compartmentSelect.options).forEach(opt => {
                if (!opt.value) return;
                opt.hidden = opt.getAttribute('data-train') !== trainId;
            });
            compartmentSelect.value = '';
            classInput.value = '';
        });
        compartmentSelect.addEventListener('change', () => {
            const cls = compartmentSelect.selectedOptions[0]?.getAttribute('data-class') || '';
            classInput.value = cls;
        });

        // Search
        document.getElementById('searchInput').addEventListener('keyup', () => {
            const term = document.getElementById('searchInput').value.toLowerCase();
            seatTableBody.querySelectorAll('tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        // Edit seat load
        document.querySelectorAll('.edit-seat-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                modalTitle.textContent = 'Edit Seat';
                methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                fetch(`/adminpanel/seats/${id}/edit`)
                    .then(r => r.json())
                    .then(s => {
                        trainSelect.value = s.train_id;
                        // trigger filter
                        const event = new Event('change');
                        trainSelect.dispatchEvent(event);
                        compartmentSelect.value = s.compartment_id;
                        classInput.value = compartmentSelect.selectedOptions[0]?.getAttribute('data-class') || '';
                        document.getElementById('seat_number').value = s.seat_number;
                        document.getElementById('is_available').value = s.is_available ? '1' : '0';
                        seatForm.action = `/adminpanel/seats/${id}`;
                    })
                    .catch(() => alert('Failed to load seat'));
            });
        });

        document.getElementById('seatModal').addEventListener('hidden.bs.modal', function () {
            modalTitle.textContent = 'Add Seat';
            methodField.innerHTML = '';
            seatForm.action = '/adminpanel/seats';
            seatForm.reset();
            classInput.value = '';
        });
    </script>
</body>

</html>