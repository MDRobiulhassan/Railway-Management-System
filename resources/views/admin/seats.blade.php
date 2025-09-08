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
    <x-navbar/>
    <div class="container mt-4">
        <h1>Train Seat Management</h1>

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
            <input type="text" id="searchInput" class="form-control w-25" placeholder="Search seats...">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#seatModal">
                <i class="fa-solid fa-plus"></i> Add Seat
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Seat ID</th>
                        <th>Train ID</th>
                        <th>Compartment ID</th>
                        <th>Seat Number</th>
                        <th>Availability</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="seatTableBody">
                    @forelse($seats as $s)
                    <tr>
                        <td>{{ $s->seat_id }}</td>
                        <td>#{{ $s->train_id }} - {{ $s->train->train_name ?? '' }}</td>
                        <td>#{{ $s->compartment_id }} - {{ $s->compartment->compartment_name ?? '' }}</td>
                        <td>{{ $s->seat_number }}</td>
                        <td><span class="badge {{ $s->is_available ? 'bg-success' : 'bg-danger' }}">{{ $s->is_available ? 'Available' : 'Unavailable' }}</span></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btnWARNING edit-seat-btn" data-bs-toggle="modal" data-bs-target="#seatModal" data-seat='@json($s)'>Edit</button>
                                <form action="{{ route('admin.seats.destroy', $s) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this seat?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger delete-seat-btn" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No seats found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @isset($seats)
        <div class="mt-3">{{ $seats->links() }}</div>
        @endisset
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
                        <input type="hidden" name="_method" id="seatFormMethod" value="POST">
                        <div class="mb-3">
                            <label>Train ID</label>
                            <input type="number" class="form-control" id="train_id" name="train_id" required>
                        </div>
                        <div class="mb-3">
                            <label>Compartment ID</label>
                            <input type="number" class="form-control" id="compartment_id" name="compartment_id" required>
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
        const modalTitle = document.getElementById('modalTitle');

        // Search seats
        document.getElementById('searchInput').addEventListener('keyup', () => {
            const term = document.getElementById('searchInput').value.toLowerCase();
            seatTableBody.querySelectorAll('tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        // Edit seat
        document.querySelectorAll('.edit-seat-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const s = JSON.parse(this.getAttribute('data-seat'));
                document.getElementById('modalTitle').textContent = 'Edit Seat';
                seatForm.action = `/adminpanel/seats/${s.seat_id}`;
                document.getElementById('seatFormMethod').value = 'PUT';
                document.getElementById('train_id').value = s.train_id;
                document.getElementById('compartment_id').value = s.compartment_id;
                document.getElementById('seat_number').value = s.seat_number;
                document.getElementById('is_available').value = s.is_available ? '1' : '0';
            });
        });

        // Default add action
        document.querySelector('[data-bs-target="#seatModal"]').addEventListener('click', () => {
            document.getElementById('modalTitle').textContent = 'Add Seat';
            seatForm.action = `{{ route('admin.seats.store') }}`;
            document.getElementById('seatFormMethod').value = 'POST';
            seatForm.reset();
        });
    </script>
</body>

</html>