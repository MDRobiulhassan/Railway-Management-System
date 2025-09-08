<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <tr data-id="1">
                        <td>1</td>
                        <td><strong>Subarna Express</strong></td>
                        <td>Dhaka</td>
                        <td>Chittagong</td>
                        <td>2025-09-05 08:00</td>
                        <td>2025-09-05 14:00</td>
                        <td>360</td>
                        <td><span class="badge bg-success">Scheduled</span></td>
                        <td>
                            <div><strong>Snigdha:</strong> 500 BDT</div>
                            <div><strong>Shovan:</strong> 1000 BDT</div>
                            <div><strong>AC:</strong> 1500 BDT</div>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-train-btn" data-id="1">Edit</button>
                                <button class="btn btn-sm btn-danger delete-train-btn" data-id="1">Delete</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
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
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Train Dropdown -->
                                <div class="mb-3">
                                    <label class="form-label">Train</label>
                                    <select class="form-select" id="train_name" required>
                                        <option value="Subarna Express">Subarna Express</option>
                                        <option value="Mohanganj Express">Mohanganj Express</option>
                                        <option value="Ekota Express">Ekota Express</option>
                                    </select>
                                </div>
                                <!-- Departure Dropdown -->
                                <div class="mb-3">
                                    <label class="form-label">Departure Station</label>
                                    <select class="form-select" id="departure" required>
                                        <option value="Dhaka">Dhaka</option>
                                        <option value="Chittagong">Chittagong</option>
                                        <option value="Sylhet">Sylhet</option>
                                        <option value="Rajshahi">Rajshahi</option>
                                    </select>
                                </div>
                                <!-- Arrival Dropdown -->
                                <div class="mb-3">
                                    <label class="form-label">Arrival Station</label>
                                    <select class="form-select" id="arrival" required>
                                        <option value="Dhaka">Dhaka</option>
                                        <option value="Chittagong">Chittagong</option>
                                        <option value="Sylhet">Sylhet</option>
                                        <option value="Rajshahi">Rajshahi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Departure Time</label>
                                    <input type="datetime-local" class="form-control" id="departure_time" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Arrival Time</label>
                                    <input type="datetime-local" class="form-control" id="arrival_time" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Duration (minutes)</label>
                                    <input type="number" class="form-control" id="duration" required min="1">
                                </div>
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

                        <!-- Ticket Pricing -->
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label">Snigdha Price</label>
                                <input type="number" class="form-control" id="economy_price" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">AC Price</label>
                                <input type="number" class="form-control" id="ac_price" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Shovan Class Price</label>
                                <input type="number" class="form-control" id="first_price" min="0" required>
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
                        <input type="hidden" id="edit_train_id">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Train Dropdown -->
                                <div class="mb-3">
                                    <label class="form-label">Train</label>
                                    <select class="form-select" id="edit_train_name" required>
                                        <option value="Subarna Express">Subarna Express</option>
                                        <option value="Mohanganj Express">Mohanganj Express</option>
                                        <option value="Ekota Express">Ekota Express</option>
                                    </select>
                                </div>
                                <!-- Departure Dropdown -->
                                <div class="mb-3">
                                    <label class="form-label">Departure Station</label>
                                    <select class="form-select" id="edit_departure" required>
                                        <option value="Dhaka">Dhaka</option>
                                        <option value="Chittagong">Chittagong</option>
                                        <option value="Sylhet">Sylhet</option>
                                        <option value="Rajshahi">Rajshahi</option>
                                    </select>
                                </div>
                                <!-- Arrival Dropdown -->
                                <div class="mb-3">
                                    <label class="form-label">Arrival Station</label>
                                    <select class="form-select" id="edit_arrival" required>
                                        <option value="Dhaka">Dhaka</option>
                                        <option value="Chittagong">Chittagong</option>
                                        <option value="Sylhet">Sylhet</option>
                                        <option value="Rajshahi">Rajshahi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Departure Time</label>
                                    <input type="datetime-local" class="form-control" id="edit_departure_time" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Arrival Time</label>
                                    <input type="datetime-local" class="form-control" id="edit_arrival_time" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Duration (minutes)</label>
                                    <input type="number" class="form-control" id="edit_duration" min="1" required>
                                </div>
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

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label">Snigdha Price</label>
                                <input type="number" class="form-control" id="edit_economy_price" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">AC Price</label>
                                <input type="number" class="form-control" id="edit_ac_price" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Shovan Class Price</label>
                                <input type="number" class="form-control" id="edit_first_price" min="0" required>
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

        // Add Train
        addForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const id = Date.now();
            const train = {
                id,
                name: document.getElementById('train_name').value,
                departure: document.getElementById('departure').value,
                arrival: document.getElementById('arrival').value,
                departure_time: document.getElementById('departure_time').value.replace('T', ' '),
                arrival_time: document.getElementById('arrival_time').value.replace('T', ' '),
                duration: document.getElementById('duration').value,
                status: document.getElementById('status').value,
                economy: document.getElementById('economy_price').value,
                ac: document.getElementById('ac_price').value,
                first: document.getElementById('first_price').value
            };

            const tr = document.createElement('tr');
            tr.setAttribute('data-id', id);
            tr.innerHTML = `
                <td>${id}</td>
                <td><strong>${train.name}</strong></td>
                <td>${train.departure}</td>
                <td>${train.arrival}</td>
                <td>${train.departure_time}</td>
                <td>${train.arrival_time}</td>
                <td>${train.duration}</td>
                <td><span class="badge bg-${train.status === 'scheduled' ? 'success' : train.status === 'delayed' ? 'warning' : train.status === 'cancelled' ? 'danger' : 'info'}">${train.status.charAt(0).toUpperCase() + train.status.slice(1)}</span></td>
                <td>
                    <div><strong>Snigdha:</strong> ${train.economy} BDT</div>
                    <div><strong>AC:</strong> ${train.ac} BDT</div>
                    <div><strong>Shovan:</strong> ${train.first} BDT</div>
                </td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-warning edit-train-btn" data-id="${id}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-train-btn" data-id="${id}">Delete</button>
                    </div>
                </td>
            `;
            tableBody.appendChild(tr);
            bootstrap.Modal.getInstance(document.getElementById('addTrainModal')).hide();
            addForm.reset();
        });

        // Edit Train
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('edit-train-btn')) {
                const id = e.target.dataset.id;
                const tr = document.querySelector(`tr[data-id="${id}"]`);
                document.getElementById('edit_train_id').value = id;
                document.getElementById('edit_train_name').value = tr.children[1].textContent.trim();
                document.getElementById('edit_departure').value = tr.children[2].textContent.trim();
                document.getElementById('edit_arrival').value = tr.children[3].textContent.trim();
                document.getElementById('edit_departure_time').value = tr.children[4].textContent.replace(' ', 'T');
                document.getElementById('edit_arrival_time').value = tr.children[5].textContent.replace(' ', 'T');
                document.getElementById('edit_duration').value = tr.children[6].textContent;
                document.getElementById('edit_status').value = tr.children[7].textContent.toLowerCase();
                document.getElementById('edit_economy_price').value = tr.children[8].children[0].textContent.split(' ')[1];
                document.getElementById('edit_ac_price').value = tr.children[8].children[1].textContent.split(' ')[1];
                document.getElementById('edit_first_price').value = tr.children[8].children[2].textContent.split(' ')[1];
                new bootstrap.Modal(document.getElementById('editTrainModal')).show();
            }
            if (e.target.classList.contains('delete-train-btn')) {
                if (confirm('Are you sure you want to delete this train?')) {
                    const id = e.target.dataset.id;
                    document.querySelector(`tr[data-id="${id}"]`).remove();
                }
            }
        });

        editForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const id = document.getElementById('edit_train_id').value;
            const tr = document.querySelector(`tr[data-id="${id}"]`);
            tr.children[1].textContent = document.getElementById('edit_train_name').value;
            tr.children[2].textContent = document.getElementById('edit_departure').value;
            tr.children[3].textContent = document.getElementById('edit_arrival').value;
            tr.children[4].textContent = document.getElementById('edit_departure_time').value.replace('T', ' ');
            tr.children[5].textContent = document.getElementById('edit_arrival_time').value.replace('T', ' ');
            tr.children[6].textContent = document.getElementById('edit_duration').value;
            const status = document.getElementById('edit_status').value;
            tr.children[7].innerHTML = `<span class="badge bg-${status === 'scheduled' ? 'success' : status === 'delayed' ? 'warning' : status === 'cancelled' ? 'danger' : 'info'}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
            tr.children[8].innerHTML = `
                <div><strong>Snigdha:</strong> ${document.getElementById('edit_economy_price').value} BDT</div>
                <div><strong>AC:</strong> ${document.getElementById('edit_ac_price').value} BDT</div>
                <div><strong>Shovan:</strong> ${document.getElementById('edit_first_price').value} BDT</div>
            `;
            bootstrap.Modal.getInstance(document.getElementById('editTrainModal')).hide();
        });
    </script>
</body>

</html>