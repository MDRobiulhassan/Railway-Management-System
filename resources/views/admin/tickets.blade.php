<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/tickets.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <x-navbar/>
    <div class="container mt-4">
        <h1>Ticket Management</h1>

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
            <input type="text" id="searchInput" class="form-control w-25" placeholder="Search tickets...">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ticketModal">
                <i class="fa-solid fa-plus"></i> Add Ticket
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Ticket ID</th>
                        <th>Booking</th>
                        <th>Seat</th>
                        <th>Class</th>
                        <th>Travel Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="ticketTableBody">
                    @forelse($tickets as $t)
                    <tr>
                        <td>{{ $t->ticket_id }}</td>
                        <td>#{{ $t->booking_id }} - {{ $t->booking->user->name ?? '' }}</td>
                        <td>#{{ $t->seat_id }} - {{ $t->seat->seat_number ?? '' }}</td>
                        <td>{{ $t->compartment->class_name ?? '-' }}</td>
                        <td>{{ optional($t->travel_date)->format('Y-m-d') }}</td>
                        <td><span class="badge {{ $t->ticket_status==='active'?'bg-success':($t->ticket_status==='cancelled'?'bg-danger':'bg-secondary') }}">{{ ucfirst($t->ticket_status) }}</span></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-ticket-btn" data-bs-toggle="modal" data-bs-target="#ticketModal" data-ticket='@json($t)'>Edit</button>
                                <form action="{{ route('admin.tickets.destroy', $t) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this ticket?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger delete-ticket-btn" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No tickets found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @isset($tickets)
        <div class="mt-3">{{ $tickets->links() }}</div>
        @endisset
    </div>

    <!-- Modal for Add/Edit Ticket -->
    <div class="modal fade" id="ticketModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="ticketForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="ticketFormMethod" value="POST">
                        <div class="mb-3">
                            <label>Booking ID</label>
                            <input type="number" class="form-control" id="booking" name="booking_id" required>
                        </div>
                        <div class="mb-3">
                            <label>Train ID</label>
                            <input type="number" class="form-control" id="train" name="train_id" required>
                        </div>
                        <div class="mb-3">
                            <label>Seat ID</label>
                            <input type="number" class="form-control" id="seat" name="seat_id" required>
                        </div>
                        <div class="mb-3">
                            <label>Compartment ID</label>
                            <input type="number" class="form-control" id="compartment" name="compartment_id" required>
                        </div>
                        <div class="mb-3">
                            <label>Travel Date</label>
                            <input type="date" class="form-control" id="date" name="travel_date" required>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-select" id="status" name="ticket_status" required>
                                <option value="active">Active</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="used">Used</option>
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
        const searchInput = document.getElementById('searchInput');
        const ticketTableBody = document.getElementById('ticketTableBody');
        const ticketForm = document.getElementById('ticketForm');
        const modalTitle = document.getElementById('modalTitle');

        // Search tickets
        searchInput.addEventListener('keyup', () => {
            const term = searchInput.value.toLowerCase();
            ticketTableBody.querySelectorAll('tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        // Populate edit
        document.querySelectorAll('.edit-ticket-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const t = JSON.parse(this.getAttribute('data-ticket'));
                modalTitle.textContent = 'Edit Ticket';
                ticketForm.action = `/adminpanel/tickets/${t.ticket_id}`;
                document.getElementById('ticketFormMethod').value = 'PUT';
                document.getElementById('booking').value = t.booking_id;
                document.getElementById('train').value = t.train_id;
                document.getElementById('seat').value = t.seat_id;
                document.getElementById('compartment').value = t.compartment_id;
                document.getElementById('date').value = t.travel_date;
                document.getElementById('status').value = t.ticket_status;
            });
        });

        // Default add
        document.querySelector('[data-bs-target="#ticketModal"]').addEventListener('click', () => {
            modalTitle.textContent = 'Add Ticket';
            ticketForm.action = `{{ route('admin.tickets.store') }}`;
            document.getElementById('ticketFormMethod').value = 'POST';
            ticketForm.reset();
        });

        // Delete ticket
        ticketTableBody.addEventListener('click', (e) => {
            if (e.target.classList.contains('delete-ticket-btn')) {
                if (confirm('Are you sure you want to delete this ticket?')) {
                    e.target.closest('tr').remove();
                }
            }
        });

        // Save changes or add new ticket
        ticketForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const booking = document.getElementById('booking').value;
            const seat = document.getElementById('seat').value;
            const cls = document.getElementById('class').value;
            const date = document.getElementById('date').value;
            const status = document.getElementById('status').value;
            const badgeClass = status === 'Active' ? 'bg-success' : status === 'Cancelled' ? 'bg-danger' : 'bg-secondary';

            if (currentEditRow) {
                // Edit existing
                currentEditRow.cells[1].textContent = booking;
                currentEditRow.cells[2].textContent = seat;
                currentEditRow.cells[3].textContent = cls;
                currentEditRow.cells[4].textContent = date;
                currentEditRow.cells[5].innerHTML = `<span class="badge ${badgeClass}">${status}</span>`;
            } else {
                // Add new
                const newRow = ticketTableBody.insertRow();
                const id = ticketTableBody.rows.length;
                newRow.innerHTML = `
            <td>${id}</td>
            <td>${booking}</td>
            <td>${seat}</td>
            <td>${cls}</td>
            <td>${date}</td>
            <td><span class="badge ${badgeClass}">${status}</span></td>
            <td>
                <div class="btn-group">
                    <button class="btn btn-sm btn-warning edit-ticket-btn">Edit</button>
                    <button class="btn btn-sm btn-danger delete-ticket-btn">Delete</button>
                </div>
            </td>`;
            }
            currentEditRow = null;
            modalTitle.textContent = 'Add Ticket';
            ticketForm.reset();
            bootstrap.Modal.getInstance(document.getElementById('ticketModal')).hide();
        });
    </script>
</body>

</html>