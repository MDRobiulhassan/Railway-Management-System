<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Compartments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/compartments.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <x-navbar />

    <div class="container">
        <h1>Train Compartment Management</h1>

        <div class="mb-3 d-flex justify-content-between align-items-center">
            <input type="text" id="searchInput" class="form-control w-25" placeholder="Search compartments...">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCompartmentModal">
                <i class="fa-solid fa-plus"></i> Add Compartment
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Compartment ID</th>
                        <th>Train Name</th>
                        <th>Compartment Name</th>
                        <th>Class Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="compartmentTableBody">
                    <!-- Sample compartments -->
                    <tr>
                        <td>1</td>
                        <td>Subarna Express</td>
                        <td>Ka</td>
                        <td>AC</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-compartment-btn" data-bs-toggle="modal"
                                    data-bs-target="#editCompartmentModal" data-id="1" data-train="Subarna Express"
                                    data-compartment="Ka" data-class="AC">Edit</button>
                                <button class="btn btn-sm btn-danger delete-compartment-btn">Delete</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Compartment Modal -->
    <div class="modal fade" id="addCompartmentModal" tabindex="-1" aria-labelledby="addCompartmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addCompartmentForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCompartmentModalLabel">Add Compartment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="add_train_name" class="form-label">Train Name</label>
                            <select class="form-select" id="add_train_name" required>
                                <option value="Subarna Express">Subarna Express</option>
                                <option value="Mohanganj Express">Mohanganj Express</option>
                                <option value="Ekota Express">Ekota Express</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="add_compartment_name" class="form-label">Compartment</label>
                            <select class="form-select" id="add_compartment_name" required>
                                <option value="Ka">Ka</option>
                                <option value="Kha">Kha</option>
                                <option value="Ga">Ga</option>
                                <option value="Gha">Gha</option>
                                <option value="Uma">Uma</option>
                                <option value="Cha">Cha</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="add_class_name" class="form-label">Class Name</label>
                            <input type="text" class="form-control" id="add_class_name" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add Compartment</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Compartment Modal -->
    <div class="modal fade" id="editCompartmentModal" tabindex="-1" aria-labelledby="editCompartmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editCompartmentForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCompartmentModalLabel">Edit Compartment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_compartment_id">
                        <div class="mb-3">
                            <label for="edit_train_name" class="form-label">Train Name</label>
                            <select class="form-select" id="edit_train_name" required>
                                <option value="Subarna Express">Subarna Express</option>
                                <option value="Mohanganj Express">Mohanganj Express</option>
                                <option value="Ekota Express">Ekota Express</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_compartment_name" class="form-label">Compartment</label>
                            <select class="form-select" id="edit_compartment_name" required>
                                <option value="Ka">Ka</option>
                                <option value="Kha">Kha</option>
                                <option value="Ga">Ga</option>
                                <option value="Gha">Gha</option>
                                <option value="Uma">Uma</option>
                                <option value="Cha">Cha</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_class_name" class="form-label">Class Name</label>
                            <input type="text" class="form-control" id="edit_class_name" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Update Compartment</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const tableBody = document.getElementById('compartmentTableBody');
        const compartmentMap = { 'Ka': 'AC', 'Kha': 'AC', 'Ga': 'Snigdha', 'Gha': 'Snigdha', 'Uma': 'Shovan', 'Cha': 'Shovan' };

        const addCompartmentName = document.getElementById('add_compartment_name');
        const addClassName = document.getElementById('add_class_name');
        const editCompartmentName = document.getElementById('edit_compartment_name');
        const editClassName = document.getElementById('edit_class_name');

        // Auto-set class based on compartment
        addCompartmentName.addEventListener('change', () => {
            addClassName.value = compartmentMap[addCompartmentName.value];
        });

        editCompartmentName.addEventListener('change', () => {
            editClassName.value = compartmentMap[editCompartmentName.value];
        });

        // Set initial class on page load
        addClassName.value = compartmentMap[addCompartmentName.value];

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const term = this.value.toLowerCase();
            tableBody.querySelectorAll('tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        // Add compartment
        document.getElementById('addCompartmentForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const train = document.getElementById('add_train_name').value;
            const compartment = addCompartmentName.value;
            const cls = addClassName.value;
            const id = tableBody.querySelectorAll('tr').length + 1;

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${id}</td>
                <td>${train}</td>
                <td>${compartment}</td>
                <td>${cls}</td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-warning edit-compartment-btn" data-bs-toggle="modal"
                            data-bs-target="#editCompartmentModal"
                            data-id="${id}" data-train="${train}" data-compartment="${compartment}" data-class="${cls}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-compartment-btn">Delete</button>
                    </div>
                </td>
            `;
            tableBody.appendChild(newRow);
            bootstrap.Modal.getInstance(document.getElementById('addCompartmentModal')).hide();
            this.reset();
            addClassName.value = compartmentMap[addCompartmentName.value];
        });

        // Edit compartment
        tableBody.addEventListener('click', function (e) {
            if (e.target.classList.contains('edit-compartment-btn')) {
                const btn = e.target;
                document.getElementById('edit_compartment_id').value = btn.dataset.id;
                document.getElementById('edit_train_name').value = btn.dataset.train;
                editCompartmentName.value = btn.dataset.compartment;
                editClassName.value = btn.dataset.class;
            } else if (e.target.classList.contains('delete-compartment-btn')) {
                if (confirm('Are you sure you want to delete this compartment?')) {
                    e.target.closest('tr').remove();
                }
            }
        });

        document.getElementById('editCompartmentForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const id = document.getElementById('edit_compartment_id').value;
            const train = document.getElementById('edit_train_name').value;
            const compartment = editCompartmentName.value;
            const cls = editClassName.value;

            tableBody.querySelectorAll('tr').forEach(row => {
                if (row.cells[0].textContent == id) {
                    row.cells[1].textContent = train;
                    row.cells[2].textContent = compartment;
                    row.cells[3].textContent = cls;
                    const btn = row.querySelector('.edit-compartment-btn');
                    btn.dataset.train = train;
                    btn.dataset.compartment = compartment;
                    btn.dataset.class = cls;
                }
            });

            bootstrap.Modal.getInstance(document.getElementById('editCompartmentModal')).hide();
        });
    </script>
</body>

</html>