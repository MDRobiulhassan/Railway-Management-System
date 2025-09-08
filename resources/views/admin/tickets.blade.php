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
                        <th>Booking ID</th>
                        <th>Booking Name</th>
                        <th>Seat</th>
                        <th>Compartment</th>
                        <th>Class</th>
                        <th>Travel Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="ticketTableBody">
                    <tr>
                        <td>1</td>
                        <td>101</td>
                        <td>Alice</td>
                        <td>1</td>
                        <td>Ka</td>
                        <td>AC</td>
                        <td>2025-09-10 09:00</td>
                        <td><span class="badge bg-success">Active</span></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-ticket-btn">Edit</button>
                                <button class="btn btn-sm btn-danger delete-ticket-btn">Delete</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
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
                    <form id="ticketForm">
                        <div class="mb-3">
                            <label>Booking</label>
                            <select class="form-select" id="booking_id" required>
                                <option value="101" data-name="Alice">Booking #101 - Alice</option>
                                <option value="102" data-name="Bob">Booking #102 - Bob</option>
                                <option value="103" data-name="Charlie">Booking #103 - Charlie</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Seat</label>
                            <select class="form-select" id="seat_name" required>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Compartment</label>
                            <select class="form-select" id="compartment_name" required>
                                <option value="Ka">Ka</option>
                                <option value="Kha">Kha</option>
                                <option value="Ga">Ga</option>
                                <option value="Gha">Gha</option>
                                <option value="Uma">Uma</option>
                                <option value="Cha">Cha</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Class</label>
                            <input type="text" class="form-control" id="class_name" readonly>
                        </div>
                        <div class="mb-3">
                            <label>Travel Date & Time</label>
                            <input type="datetime-local" class="form-control" id="travel_date" required>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-select" id="status">
                                <option value="Active">Active</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="Used">Used</option>
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
        const ticketTableBody = document.getElementById('ticketTableBody');
        const ticketForm = document.getElementById('ticketForm');
        const modalTitle = document.getElementById('modalTitle');

        const compartmentMap = { 'Ka': 'AC', 'Kha': 'AC', 'Ga': 'Snigdha', 'Gha': 'Snigdha', 'Uma': 'Shovan', 'Cha': 'Shovan' };

        const compartmentSelect = document.getElementById('compartment_name');
        const classInput = document.getElementById('class_name');
        const bookingSelect = document.getElementById('booking_id');

        let currentEditRow = null;

        // Auto-set class based on compartment
        compartmentSelect.addEventListener('change', () => {
            classInput.value = compartmentMap[compartmentSelect.value];
        });

        classInput.value = compartmentMap[compartmentSelect.value];

        // Search tickets
        document.getElementById('searchInput').addEventListener('keyup', () => {
            const term = document.getElementById('searchInput').value.toLowerCase();
            ticketTableBody.querySelectorAll('tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        // Delete ticket
        ticketTableBody.addEventListener('click', (e) => {
            if (e.target.classList.contains('delete-ticket-btn')) {
                if (confirm('Are you sure you want to delete this ticket?')) {
                    e.target.closest('tr').remove();
                }
            }
        });

        // Edit ticket
        ticketTableBody.addEventListener('click', (e) => {
            if (e.target.classList.contains('edit-ticket-btn')) {
                currentEditRow = e.target.closest('tr');
                modalTitle.textContent = 'Edit Ticket';

                const bookingId = currentEditRow.cells[1].textContent;
                const bookingName = currentEditRow.cells[2].textContent;
                for (let option of bookingSelect.options) {
                    if (option.value === bookingId) {
                        option.selected = true;
                        break;
                    }
                }

                document.getElementById('seat_name').value = currentEditRow.cells[3].textContent;
                compartmentSelect.value = currentEditRow.cells[4].textContent;
                classInput.value = currentEditRow.cells[5].textContent;
                document.getElementById('travel_date').value = currentEditRow.cells[6].textContent;
                document.getElementById('status').value = currentEditRow.cells[7].textContent.trim();

                new bootstrap.Modal(document.getElementById('ticketModal')).show();
            }
        });

        // Save changes or add new ticket
        ticketForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const bookingOption = bookingSelect.selectedOptions[0];
            const bookingId = bookingOption.value;
            const bookingName = bookingOption.dataset.name;
            const seat = document.getElementById('seat_name').value;
            const compartment = compartmentSelect.value;
            const cls = classInput.value;
            const travelDate = document.getElementById('travel_date').value;
            const status = document.getElementById('status').value;
            const badgeClass = status === 'Active' ? 'bg-success' : status === 'Cancelled' ? 'bg-danger' : 'bg-secondary';

            if (currentEditRow) {
                currentEditRow.cells[1].textContent = bookingId;
                currentEditRow.cells[2].textContent = bookingName;
                currentEditRow.cells[3].textContent = seat;
                currentEditRow.cells[4].textContent = compartment;
                currentEditRow.cells[5].textContent = cls;
                currentEditRow.cells[6].textContent = travelDate;
                currentEditRow.cells[7].innerHTML = `<span class="badge ${badgeClass}">${status}</span>`;
            } else {
                const newRow = ticketTableBody.insertRow();
                const id = ticketTableBody.rows.length + 1;
                newRow.innerHTML = `
                <td>${id}</td>
                <td>${bookingId}</td>
                <td>${bookingName}</td>
                <td>${seat}</td>
                <td>${compartment}</td>
                <td>${cls}</td>
                <td>${travelDate}</td>
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
            classInput.value = compartmentMap[compartmentSelect.value];
            bootstrap.Modal.getInstance(document.getElementById('ticketModal')).hide();
        });
    </script>
</body>

</html>