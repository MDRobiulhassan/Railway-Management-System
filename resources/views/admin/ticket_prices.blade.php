<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Ticket Prices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/ticket_prices.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Ticket Prices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/ticket_prices.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="container mt-4">
        <h1>Train Ticket Price Management</h1>
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
            <input type="text" id="searchInput" class="form-control w-25" placeholder="Search prices...">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#priceModal">
                <i class="fa-solid fa-plus"></i> Add Price
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Price ID</th>
                        <th>Train ID</th>
                        <th>Compartment</th>
                        <th>Base Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="priceTableBody">
                    @forelse($prices as $p)
                    <tr>
                        <td>{{ $p->price_id }}</td>
                        <td>#{{ $p->train_id }} - {{ $p->train->train_name ?? '' }}</td>
                        <td>#{{ $p->compartment_id }} - {{ $p->compartment->compartment_name ?? '' }}</td>
                        <td>{{ number_format($p->base_price,2) }} BDT</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning edit-price-btn" data-bs-toggle="modal" data-bs-target="#priceModal" data-price='@json($p)'>Edit</button>
                                <form action="{{ route('admin.ticket_prices.destroy', $p) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this price?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger delete-price-btn" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No prices found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @isset($prices)
        <div class="mt-3">{{ $prices->links() }}</div>
        @endisset
    </div>

    <!-- Modal for Add/Edit Price -->
    <div class="modal fade" id="priceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Ticket Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="priceForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="priceFormMethod" value="POST">
                        <div class="mb-3">
                            <label>Train ID</label>
                            <input type="number" class="form-control" id="train_id" name="train_id" required>
                        </div>
                        <div class="mb-3">
                            <label>Compartment ID</label>
                            <input type="number" class="form-control" id="compartment_id" name="compartment_id" required>
                        </div>
                        <div class="mb-3">
                            <label>Base Price</label>
                            <input type="number" class="form-control" id="base_price" name="base_price" min="0" step="0.01" placeholder="0.00" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const priceTableBody = document.getElementById('priceTableBody');
        const priceForm = document.getElementById('priceForm');
        const modalTitle = document.getElementById('modalTitle');

        // Search
        document.getElementById('searchInput').addEventListener('keyup', () => {
            const term = document.getElementById('searchInput').value.toLowerCase();
            priceTableBody.querySelectorAll('tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        // Delete
        priceTableBody.addEventListener('click', e => {
            if (e.target.classList.contains('delete-price-btn')) {
                if (confirm('Delete this price?')) {
                    e.target.closest('tr').remove();
                }
            }
        });

        // Populate edit
        document.querySelectorAll('.edit-price-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const p = JSON.parse(this.getAttribute('data-price'));
                modalTitle.textContent = 'Edit Ticket Price';
                priceForm.action = `/adminpanel/ticket_prices/${p.price_id}`;
                document.getElementById('priceFormMethod').value = 'PUT';
                document.getElementById('train_id').value = p.train_id;
                document.getElementById('compartment_id').value = p.compartment_id;
                document.getElementById('base_price').value = p.base_price;
            });
        });

        // Default add
        document.querySelector('[data-bs-target="#priceModal"]').addEventListener('click', () => {
            modalTitle.textContent = 'Add Ticket Price';
            priceForm.action = `{{ route('admin.ticket_prices.store') }}`;
            document.getElementById('priceFormMethod').value = 'POST';
            priceForm.reset();
        });

        // Save/Add
        priceForm.addEventListener('submit', e => {
            e.preventDefault();
            const train_id = document.getElementById('train_id').value;
            const compartment = document.getElementById('compartment_id').value;
            const base_price = parseFloat(document.getElementById('base_price').value).toFixed(2);

            if (currentEditRow) {
                currentEditRow.cells[1].textContent = train_id;
                currentEditRow.cells[2].textContent = compartment;
                currentEditRow.cells[3].textContent = `$${base_price}`;
            } else {
                const newRow = priceTableBody.insertRow();
                const id = priceTableBody.rows.length + 1;
                newRow.innerHTML = `
            <td>${id}</td>
            <td>${train_id}</td>
            <td>${compartment}</td>
            <td>$${base_price}</td>
            <td>
                <div class="btn-group">
                    <button class="btn btn-sm btn-warning edit-price-btn">Edit</button>
                    <button class="btn btn-sm btn-danger delete-price-btn">Delete</button>
                </div>
            </td>`;
            }

            currentEditRow = null;
            modalTitle.textContent = 'Add Ticket Price';
            priceForm.reset();
            bootstrap.Modal.getInstance(document.getElementById('priceModal')).hide();
        });
    </script>
</body>

</html>

</html>