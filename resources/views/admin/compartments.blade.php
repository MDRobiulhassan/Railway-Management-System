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
                        <th>Train ID</th>
                        <th>Compartment Name</th>
                        <th>Class Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="compartmentTableBody">
                    <!-- AC Class -->
                    <tr>
                        <td>1</td>
                        <td>101</td>
                        <td>Ka</td>
                        <td>AC</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-compartment-btn" data-bs-toggle="modal"
                                    data-bs-target="#editCompartmentModal" data-id="1" data-train="101" data-name="Ka"
                                    data-class="AC">Edit</button>
                                <button class="btn btn-sm btn-danger delete-compartment-btn">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>101</td>
                        <td>Kha</td>
                        <td>AC</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-compartment-btn" data-bs-toggle="modal"
                                    data-bs-target="#editCompartmentModal" data-id="2" data-train="101" data-name="Kha"
                                    data-class="AC">Edit</button>
                                <button class="btn btn-sm btn-danger delete-compartment-btn">Delete</button>
                            </div>
                        </td>
                    </tr>

                    <!-- Shovan Class -->
                    <tr>
                        <td>3</td>
                        <td>101</td>
                        <td>Ga</td>
                        <td>Shovan</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-compartment-btn" data-bs-toggle="modal"
                                    data-bs-target="#editCompartmentModal" data-id="3" data-train="102" data-name="Ga"
                                    data-class="Shovan">Edit</button>
                                <button class="btn btn-sm btn-danger delete-compartment-btn">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>101</td>
                        <td>Gha</td>
                        <td>Shovan</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-compartment-btn" data-bs-toggle="modal"
                                    data-bs-target="#editCompartmentModal" data-id="4" data-train="102" data-name="Gha"
                                    data-class="Shovan">Edit</button>
                                <button class="btn btn-sm btn-danger delete-compartment-btn">Delete</button>
                            </div>
                        </td>
                    </tr>

                    <!-- Snigdha Class -->
                    <tr>
                        <td>5</td>
                        <td>101</td>
                        <td>Uma</td>
                        <td>Snigdha</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-compartment-btn" data-bs-toggle="modal"
                                    data-bs-target="#editCompartmentModal" data-id="5" data-train="103" data-name="Uma"
                                    data-class="Snigdha">Edit</button>
                                <button class="btn btn-sm btn-danger delete-compartment-btn">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>101</td>
                        <td>Cha</td>
                        <td>Snigdha</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-compartment-btn" data-bs-toggle="modal"
                                    data-bs-target="#editCompartmentModal" data-id="6" data-train="103" data-name="Cha"
                                    data-class="Snigdha">Edit</button>
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
                            <label for="add_train_id" class="form-label">Train ID</label>
                            <input type="number" class="form-control" id="add_train_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="add_compartment_name" class="form-label">Compartment Name</label>
                            <input type="text" class="form-control" id="add_compartment_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="add_class_name" class="form-label">Class Name</label>
                            <input type="text" class="form-control" id="add_class_name" required>
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
                            <label for="edit_train_id" class="form-label">Train ID</label>
                            <input type="number" class="form-control" id="edit_train_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_compartment_name" class="form-label">Compartment Name</label>
                            <input type="text" class="form-control" id="edit_compartment_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_class_name" class="form-label">Class Name</label>
                            <input type="text" class="form-control" id="edit_class_name" required>
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

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const term = this.value.toLowerCase();
            tableBody.querySelectorAll('tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });

        // Add compartment
        document.getElementById('addCompartmentForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const train = document.getElementById('add_train_id').value;
            const name = document.getElementById('add_compartment_name').value;
            const cls = document.getElementById('add_class_name').value;
            const id = tableBody.querySelectorAll('tr').length + 1;

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${id}</td>
                <td>${train}</td>
                <td>${name}</td>
                <td>${cls}</td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-warning edit-compartment-btn" data-bs-toggle="modal"
                            data-bs-target="#editCompartmentModal"
                            data-id="${id}" data-train="${train}" data-name="${name}" data-class="${cls}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-compartment-btn">Delete</button>
                    </div>
                </td>
            `;
            tableBody.appendChild(newRow);
            bootstrap.Modal.getInstance(document.getElementById('addCompartmentModal')).hide();
            this.reset();
        });

        // Edit compartment
        tableBody.addEventListener('click', function (e) {
            if (e.target.classList.contains('edit-compartment-btn')) {
                const btn = e.target;
                document.getElementById('edit_compartment_id').value = btn.dataset.id;
                document.getElementById('edit_train_id').value = btn.dataset.train;
                document.getElementById('edit_compartment_name').value = btn.dataset.name;
                document.getElementById('edit_class_name').value = btn.dataset.class;
            } else if (e.target.classList.contains('delete-compartment-btn')) {
                if (confirm('Are you sure you want to delete this compartment?')) {
                    e.target.closest('tr').remove();
                }
            }
        });

        document.getElementById('editCompartmentForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const id = document.getElementById('edit_compartment_id').value;
            const train = document.getElementById('edit_train_id').value;
            const name = document.getElementById('edit_compartment_name').value;
            const cls = document.getElementById('edit_class_name').value;

            tableBody.querySelectorAll('tr').forEach(row => {
                if (row.cells[0].textContent == id) {
                    row.cells[1].textContent = train;
                    row.cells[2].textContent = name;
                    row.cells[3].textContent = cls;
                    const btn = row.querySelector('.edit-compartment-btn');
                    btn.dataset.train = train;
                    btn.dataset.name = name;
                    btn.dataset.class = cls;
                }
            });

            bootstrap.Modal.getInstance(document.getElementById('editCompartmentModal')).hide();
        });
    </script>
</body>

</html>