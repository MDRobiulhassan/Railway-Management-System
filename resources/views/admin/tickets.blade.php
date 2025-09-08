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
                    <tr>
                        <td>1</td>
                        <td>Booking #101 - Alice</td>
                        <td>Ka</td>
                        <td>AC</td>
                        <td>2025-09-10</td>
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
                            <input type="text" class="form-control" id="booking">
                        </div>
                        <div class="mb-3">
                            <label>Seat</label>
                            <input type="text" class="form-control" id="seat">
                        </div>
                        <div class="mb-3">
                            <label>Class</label>
                            <input type="text" class="form-control" id="class">
                        </div>
                        <div class="mb-3">
                            <label>Travel Date</label>
                            <input type="date" class="form-control" id="date">
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
        const searchInput = document.getElementById('searchInput');
        const ticketTableBody = document.getElementById('ticketTableBody');
        const ticketForm = document.getElementById('ticketForm');
        const modalTitle = document.getElementById('modalTitle');
        let currentEditRow = null;

        // Search tickets
        searchInput.addEventListener('keyup', () => {
            const term = searchInput.value.toLowerCase();
            ticketTableBody.querySelectorAll('tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        // Open modal for edit
        ticketTableBody.addEventListener('click', (e) => {
            if (e.target.classList.contains('edit-ticket-btn')) {
                currentEditRow = e.target.closest('tr');
                modalTitle.textContent = 'Edit Ticket';
                document.getElementById('booking').value = currentEditRow.cells[1].textContent;
                document.getElementById('seat').value = currentEditRow.cells[2].textContent;
                document.getElementById('class').value = currentEditRow.cells[3].textContent;
                document.getElementById('date').value = currentEditRow.cells[4].textContent;
                document.getElementById('status').value = currentEditRow.cells[5].textContent.trim();
                new bootstrap.Modal(document.getElementById('ticketModal')).show();
            }
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