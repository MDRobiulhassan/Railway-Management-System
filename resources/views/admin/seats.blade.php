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
                    <tr>
                        <td>1</td>
                        <td>101</td>
                        <td>A1</td>
                        <td>1</td>
                        <td><span class="badge bg-success">Available</span></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-seat-btn">Edit</button>
                                <button class="btn btn-sm btn-danger delete-seat-btn">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>101</td>
                        <td>A1</td>
                        <td>2</td>
                        <td><span class="badge bg-danger">Unavailable</span></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-seat-btn">Edit</button>
                                <button class="btn btn-sm btn-danger delete-seat-btn">Delete</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
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
                    <form id="seatForm">
                        <div class="mb-3">
                            <label>Train ID</label>
                            <input type="text" class="form-control" id="train_id" required>
                        </div>
                        <div class="mb-3">
                            <label>Compartment ID</label>
                            <input type="text" class="form-control" id="compartment_id" required>
                        </div>
                        <div class="mb-3">
                            <label>Seat Number</label>
                            <input type="text" class="form-control" id="seat_number" required>
                        </div>
                        <div class="mb-3">
                            <label>Availability</label>
                            <select class="form-select" id="is_available" required>
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
        let currentEditRow = null;

        // Search seats
        document.getElementById('searchInput').addEventListener('keyup', () => {
            const term = document.getElementById('searchInput').value.toLowerCase();
            seatTableBody.querySelectorAll('tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        // Delete seat
        seatTableBody.addEventListener('click', e => {
            if (e.target.classList.contains('delete-seat-btn')) {
                if (confirm('Are you sure you want to delete this seat?')) {
                    e.target.closest('tr').remove();
                }
            }
        });

        // Edit seat
        seatTableBody.addEventListener('click', e => {
            if (e.target.classList.contains('edit-seat-btn')) {
                currentEditRow = e.target.closest('tr');
                modalTitle.textContent = 'Edit Seat';
                document.getElementById('train_id').value = currentEditRow.cells[1].textContent;
                document.getElementById('compartment_id').value = currentEditRow.cells[2].textContent;
                document.getElementById('seat_number').value = currentEditRow.cells[3].textContent;
                document.getElementById('is_available').value = currentEditRow.cells[4].textContent.includes('Available') ? '1' : '0';
                new bootstrap.Modal(document.getElementById('seatModal')).show();
            }
        });

        // Save changes or add new seat
        seatForm.addEventListener('submit', e => {
            e.preventDefault();
            const train_id = document.getElementById('train_id').value;
            const compartment_id = document.getElementById('compartment_id').value;
            const seat_number = document.getElementById('seat_number').value;
            const is_available = document.getElementById('is_available').value === '1';
            const badgeClass = is_available ? 'bg-success' : 'bg-danger';
            const badgeText = is_available ? 'Available' : 'Unavailable';

            if (currentEditRow) {
                currentEditRow.cells[1].textContent = train_id;
                currentEditRow.cells[2].textContent = compartment_id;
                currentEditRow.cells[3].textContent = seat_number;
                currentEditRow.cells[4].innerHTML = `<span class="badge ${badgeClass}">${badgeText}</span>`;
            } else {
                const newRow = seatTableBody.insertRow();
                const id = seatTableBody.rows.length + 1;
                newRow.innerHTML = `
            <td>${id}</td>
            <td>${train_id}</td>
            <td>${compartment_id}</td>
            <td>${seat_number}</td>
            <td><span class="badge ${badgeClass}">${badgeText}</span></td>
            <td>
                <div class="btn-group">
                    <button class="btn btn-sm btn-warning edit-seat-btn">Edit</button>
                    <button class="btn btn-sm btn-danger delete-seat-btn">Delete</button>
                </div>
            </td>`;
            }

            currentEditRow = null;
            modalTitle.textContent = 'Add Seat';
            seatForm.reset();
            bootstrap.Modal.getInstance(document.getElementById('seatModal')).hide();
        });
    </script>
</body>

</html>