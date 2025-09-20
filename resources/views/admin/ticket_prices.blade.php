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
    <x-navbar />
    <div class="container mt-4">
        <h1>Train Ticket Price Management</h1>

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
            <input type="text" id="searchInput" class="form-control w-25" placeholder="Search prices...">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#priceModal">
                <i class="fa-solid fa-plus"></i> Add Price
            </button>
        </div>

        @forelse($trains as $trainId => $train)
            @if(isset($ticketPrices[$trainId]) && $ticketPrices[$trainId]->isNotEmpty())
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            {{ $train->train_name ?? 'N/A' }}
                            <span class="badge bg-light text-dark ms-2">
                                {{ $ticketPrices[$trainId]->count() }} price entries
                            </span>
                        </h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Price ID</th>
                                    <th>Compartment</th>
                                    <th>Class</th>
                                    <th>Base Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ticketPrices[$trainId] as $price)
                                    <tr>
                                        <td>{{ $price->price_id }}</td>
                                        <td>{{ $price->compartment->compartment_name ?? 'N/A' }}</td>
                                        <td>
                                            @php($className = optional($price->compartment)->class_name)
                                            <span class="badge 
                                                @if($className === 'AC') bg-success
                                                @elseif($className === 'Snigdha') bg-warning
                                                @elseif($className === 'Shovan') bg-info
                                                @else bg-secondary
                                                @endif">
                                                {{ $className ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>৳{{ number_format($price->base_price, 2) }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-warning edit-price-btn" 
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#priceModal" 
                                                    data-price-id="{{ $price->price_id }}">
                                                    Edit
                                                </button>
                                                <form action="{{ route('admin.ticket_prices.destroy', $price->price_id) }}" 
                                                    method="POST" style="display:inline;"
                                                    onsubmit="return confirm('Are you sure you want to delete this price?')">
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
                        @if($loop->last && $paginatedTicketPrices->hasPages())
                            <div class="card-footer">
                                <div class="d-flex justify-content-center">
                                    {{ $paginatedTicketPrices->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @empty
            <div class="alert alert-info">No ticket prices found</div>
        @endforelse
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
                        <div id="method-field"></div>
                        <div class="mb-3">
                            <label>Train</label>
                            <select class="form-select" id="train_id" name="train_id" required>
                                <option value="">Select Train...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Compartment</label>
                            <select class="form-select" id="compartment_id" name="compartment_id" required>
                                <option value="">Select Compartment...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Class</label>
                            <input type="text" class="form-control" id="class_name" readonly>
                        </div>
                        <div class="mb-3">
                            <label>Base Price (৳)</label>
                            <input type="number" class="form-control" id="base_price" name="base_price" min="0" step="0.01"
                                placeholder="0.00" required>
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
        const priceForm = document.getElementById('priceForm');
        const modalTitle = document.getElementById('modalTitle');
        const methodField = document.getElementById('method-field');

        const trainSelect = document.getElementById('train_id');
        const compartmentSelect = document.getElementById('compartment_id');
        const classInput = document.getElementById('class_name');

        let currentEditId = null;
        let trains = [];
        let compartments = [];

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure default action is set for Add
            priceForm.action = '/adminpanel/ticket_prices';
            loadTrains();
            loadCompartments();
        });

        // Load trains
        async function loadTrains() {
            try {
                const response = await fetch('/adminpanel/api/trains');
                trains = await response.json();
                
                trainSelect.innerHTML = '<option value="">Select Train...</option>';
                trains.forEach(train => {
                    const option = document.createElement('option');
                    option.value = train.train_id;
                    option.textContent = train.train_name;
                    trainSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading trains:', error);
            }
        }

        // Load compartments
        async function loadCompartments() {
            try {
                const response = await fetch('/adminpanel/api/compartments');
                compartments = await response.json();
                
                compartmentSelect.innerHTML = '<option value="">Select Compartment...</option>';
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

        // Auto-set class based on compartment
        compartmentSelect.addEventListener('change', () => {
            const selectedOption = compartmentSelect.selectedOptions[0];
            if (selectedOption && selectedOption.dataset.className) {
                classInput.value = selectedOption.dataset.className;
            } else {
                classInput.value = '';
            }
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

        // Edit price functionality
        document.querySelectorAll('.edit-price-btn').forEach(button => {
            button.addEventListener('click', function() {
                currentEditId = this.getAttribute('data-price-id');
                modalTitle.textContent = 'Edit Ticket Price';
                
                // Add method field for PUT request
                methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                
                // Fetch price data
                fetch(`/adminpanel/ticket_prices/${currentEditId}/edit`)
                    .then(response => response.json())
                    .then(price => {
                        // Populate form fields
                        trainSelect.value = price.train_id;
                        compartmentSelect.value = price.compartment_id;
                        classInput.value = price.compartment.class_name;
                        document.getElementById('base_price').value = price.base_price;
                        
                        // Update form action
                        priceForm.action = `/adminpanel/ticket_prices/${currentEditId}`;
                    })
                    .catch(error => {
                        console.error('Error fetching price data:', error);
                        alert('Error loading price data');
                    });
            });
        });

        // Reset form for new price
        document.getElementById('priceModal').addEventListener('hidden.bs.modal', function () {
            currentEditId = null;
            modalTitle.textContent = 'Add Ticket Price';
            methodField.innerHTML = '';
            priceForm.action = '/adminpanel/ticket_prices';
            priceForm.reset();
            classInput.value = '';
        });

        // Form submission
        priceForm.addEventListener('submit', function(e) {
            // Form will submit normally to the backend
        });
    </script>
</body>

</html>