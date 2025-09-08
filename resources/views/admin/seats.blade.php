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
                        <th>Train Name</th>
                        <th>Compartment</th>
                        <th>Class Name</th>
                        <th>Seat Number</th>
                        <th>Availability</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="seatTableBody">
                    <tr>
                        <td>1</td>
                        <td>Subarna Express</td>
                        <td>Ka</td>
                        <td>AC</td>
                        <td>1</td>
                        <td><span class="badge bg-success">Available</span></td>
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
                            <label>Train Name</label>
                            <select class="form-select" id="train_name" required>
                                <option value="Subarna Express">Subarna Express</option>
                                <option value="Mohanganj Express">Mohanganj Express</option>
                                <option value="Ekota Express">Ekota Express</option>
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
                            <label>Class Name</label>
                            <input type="text" class="form-control" id="class_name" readonly>
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
        const compartmentMap = { 'Ka': 'AC', 'Kha': 'AC', 'Ga': 'Snigdha', 'Gha': 'Snigdha', 'Uma': 'Shovan', 'Cha': 'Shovan' };

        const compartmentSelect = document.getElementById('compartment_name');
        const classInput = document.getElementById('class_name');

        let currentEditRow = null;

        // Auto-set class based on compartment
        compartmentSelect.addEventListener('change', () => {
            classInput.value = compartmentMap[compartmentSelect.value];
        });

        // Initialize
        classInput.value = compartmentMap[compartmentSelect.value];

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
                document.getElementById('train_name').value = currentEditRow.cells[1].textContent;
                compartmentSelect.value = currentEditRow.cells[2].textContent;
                classInput.value = currentEditRow.cells[3].textContent;
                document.getElementById('seat_number').value = currentEditRow.cells[4].textContent;
                document.getElementById('is_available').value = currentEditRow.cells[5].textContent.includes('Available') ? '1' : '0';
                new bootstrap.Modal(document.getElementById('seatModal')).show();
            }
        });

        // Save changes or add new seat
        seatForm.addEventListener('submit', e => {
            e.preventDefault();
            const train_name = document.getElementById('train_name').value;
            const compartment_name = compartmentSelect.value;
            const class_name = classInput.value;
            const seat_number = document.getElementById('seat_number').value;
            const is_available = document.getElementById('is_available').value === '1';
            const badgeClass = is_available ? 'bg-success' : 'bg-danger';
            const badgeText = is_available ? 'Available' : 'Unavailable';

            if (currentEditRow) {
                currentEditRow.cells[1].textContent = train_name;
                currentEditRow.cells[2].textContent = compartment_name;
                currentEditRow.cells[3].textContent = class_name;
                currentEditRow.cells[4].textContent = seat_number;
                currentEditRow.cells[5].innerHTML = `<span class="badge ${badgeClass}">${badgeText}</span>`;
            } else {
                const newRow = seatTableBody.insertRow();
                const id = seatTableBody.rows.length + 1;
                newRow.innerHTML = `
            <td>${id}</td>
            <td>${train_name}</td>
            <td>${compartment_name}</td>
            <td>${class_name}</td>
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
            classInput.value = compartmentMap[compartmentSelect.value];
            bootstrap.Modal.getInstance(document.getElementById('seatModal')).hide();
        });
    </script>
</body>

</html>