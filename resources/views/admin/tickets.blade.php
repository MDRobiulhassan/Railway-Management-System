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
    <x-navbar />
    <div class="container mt-4">
        <h1>Ticket Management</h1>

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
            <input type="text" id="searchInput" class="form-control w-25" placeholder="Search tickets...">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ticketModal">
                <i class="fa-solid fa-plus"></i> Add Ticket
            </button>
        </div>

        @forelse($ticketsBySchedule as $scheduleKey => $scheduleData)
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        {{ $scheduleData['train_name'] }} - {{ $scheduleData['route'] }}
                        <small class="ms-2">{{ $scheduleData['departure_time'] }}</small>
                        <span class="badge bg-light text-dark ms-2">
                            {{ $scheduleData['tickets']->count() }} tickets
                        </span>
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Ticket ID</th>
                                <th>Booking ID</th>
                                <th>Passenger Name</th>
                                <th>Seat</th>
                                <th>Compartment</th>
                                <th>Class</th>
                                <th>Travel Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($scheduleData['tickets'] as $ticket)
                                <tr>
                                    <td>{{ $ticket->ticket_id }}</td>
                                    <td>{{ $ticket->booking_id }}</td>
                                    <td>{{ $ticket->booking->user->name ?? 'N/A' }}</td>
                                    <td>{{ $ticket->seat->seat_number ?? 'N/A' }}</td>
                                    <td>{{ $ticket->compartment->compartment_name ?? 'N/A' }}</td>
                                    <td>{{ $ticket->compartment->class_name ?? 'N/A' }}</td>
                                    <td>{{ $ticket->travel_date ? $ticket->travel_date->format('Y-m-d') : 'N/A' }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($ticket->ticket_status === 'active') bg-success
                                            @elseif($ticket->ticket_status === 'cancelled') bg-danger
                                            @elseif($ticket->ticket_status === 'used') bg-secondary
                                            @else bg-warning
                                            @endif">
                                            {{ ucfirst($ticket->ticket_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-warning edit-ticket-btn" data-bs-toggle="modal"
                                                data-bs-target="#ticketModal" data-ticket-id="{{ $ticket->ticket_id }}">Edit</button>
                                            
                                            <form action="{{ route('admin.tickets.destroy', $ticket->ticket_id) }}" method="POST" style="display:inline;"
                                                onsubmit="return confirm('Are you sure you want to delete this ticket?')">
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
                    @if($loop->last && $paginatedTickets->hasPages())
                        <div class="card-footer">
                            <div class="d-flex justify-content-center">
                                {{ $paginatedTickets->links('pagination.bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="alert alert-info">No tickets found</div>
        @endforelse
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
                        <div id="method-field"></div>
                        <div class="mb-3">
                            <label>Booking</label>
                            <select class="form-select" id="booking_id" name="booking_id" required>
                                <option value="">Select a booking...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Compartment</label>
                            <select class="form-select" id="compartment_id" name="compartment_id" required>
                                <option value="">Select a compartment...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Seat</label>
                            <select class="form-select" id="seat_id" name="seat_id" required>
                                <option value="">Select a seat...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Class</label>
                            <input type="text" class="form-control" id="class_name" readonly>
                        </div>
                        <div class="mb-3">
                            <label>Travel Date</label>
                            <input type="date" class="form-control" id="travel_date" name="travel_date" required>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-select" id="ticket_status" name="ticket_status" required>
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
        const ticketForm = document.getElementById('ticketForm');
        const modalTitle = document.getElementById('modalTitle');
        const methodField = document.getElementById('method-field');

        const bookingSelect = document.getElementById('booking_id');
        const compartmentSelect = document.getElementById('compartment_id');
        const seatSelect = document.getElementById('seat_id');
        const classInput = document.getElementById('class_name');

        let currentEditId = null;
        let bookings = [];
        let compartments = [];
        let seats = [];

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadBookings();
            loadCompartments();
            loadSeats();
        });

        // Load bookings
        async function loadBookings() {
            try {
                const response = await fetch('/adminpanel/api/bookings');
                bookings = await response.json();
                
                bookingSelect.innerHTML = '<option value="">Select a booking...</option>';
                bookings.forEach(booking => {
                    const option = document.createElement('option');
                    option.value = booking.booking_id;
                    option.textContent = `Booking #${booking.booking_id} - ${booking.user.name}`;
                    bookingSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading bookings:', error);
            }
        }

        // Load compartments
        async function loadCompartments() {
            try {
                const response = await fetch('/adminpanel/api/compartments');
                compartments = await response.json();
                
                compartmentSelect.innerHTML = '<option value="">Select a compartment...</option>';
                compartments.forEach(compartment => {
                    const option = document.createElement('option');
                    option.value = compartment.compartment_id;
                    option.textContent = `${compartment.compartment_name} (${compartment.class_name})`;
                    option.dataset.className = compartment.class_name;
                    compartmentSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading compartments:', error);
            }
        }

        // Load seats
        async function loadSeats() {
            try {
                const response = await fetch('/adminpanel/api/seats');
                seats = await response.json();
                
                seatSelect.innerHTML = '<option value="">Select a seat...</option>';
                seats.forEach(seat => {
                    const option = document.createElement('option');
                    option.value = seat.seat_id;
                    option.textContent = `Seat ${seat.seat_number}`;
                    option.dataset.compartmentId = seat.compartment_id;
                    seatSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading seats:', error);
            }
        }

        // Auto-set class based on compartment
        compartmentSelect.addEventListener('change', () => {
            const selectedOption = compartmentSelect.selectedOptions[0];
            if (selectedOption && selectedOption.dataset.className) {
                classInput.value = selectedOption.dataset.className;
            } else {
                classInput.value = '';
            }
        });

        // Filter seats based on compartment
        compartmentSelect.addEventListener('change', () => {
            const compartmentId = compartmentSelect.value;
            seatSelect.innerHTML = '<option value="">Select a seat...</option>';
            
            seats.forEach(seat => {
                if (seat.compartment_id == compartmentId) {
                    const option = document.createElement('option');
                    option.value = seat.seat_id;
                    option.textContent = `Seat ${seat.seat_number}`;
                    seatSelect.appendChild(option);
                }
            });
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Edit ticket functionality
        document.querySelectorAll('.edit-ticket-btn').forEach(button => {
            button.addEventListener('click', function() {
                currentEditId = this.getAttribute('data-ticket-id');
                modalTitle.textContent = 'Edit Ticket';

                // Add method field for PUT request
                methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                
                // Fetch ticket data
                fetch(`/adminpanel/tickets/${currentEditId}/edit`)
                    .then(response => response.json())
                    .then(ticket => {
                        // Populate form fields
                        bookingSelect.value = ticket.booking_id;
                        compartmentSelect.value = ticket.compartment_id;
                        seatSelect.value = ticket.seat_id;
                        classInput.value = ticket.compartment.class_name;
                        document.getElementById('travel_date').value = ticket.travel_date;
                        document.getElementById('ticket_status').value = ticket.ticket_status;
                        
                        // Update form action
                        ticketForm.action = `/adminpanel/tickets/${currentEditId}`;
                    })
                    .catch(error => {
                        console.error('Error fetching ticket data:', error);
                        alert('Error loading ticket data');
                    });
            });
        });

        // Reset form for new ticket
        document.getElementById('ticketModal').addEventListener('hidden.bs.modal', function () {
            currentEditId = null;
            modalTitle.textContent = 'Add Ticket';
            methodField.innerHTML = '';
            ticketForm.action = '/adminpanel/tickets';
            ticketForm.reset();
            classInput.value = '';
        });

        // Form submission
        ticketForm.addEventListener('submit', function(e) {
            // Form will submit normally to the backend
        });
    </script>
</body>

</html>