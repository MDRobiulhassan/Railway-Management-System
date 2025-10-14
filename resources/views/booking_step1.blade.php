<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Step 1: Choose Seat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/booking.css') }}" />
</head>

<body>
    <div class="container mt-5 text-center">
        <h2 class="fw-bold mb-4">Step 1: Choose Your Seat</h2>

        <!-- Train Info -->
        <div class="alert alert-info mb-4">
            <strong>{{ $schedule->train->train_name }}</strong> - 
            {{ $schedule->sourceStation->name }} to {{ $schedule->destinationStation->name }} - 
            {{ $schedule->departure_time->format('M d, Y H:i') }}
            @if(Auth::check() && $existingTickets > 0)
                <br><small class="text-dark">You already have {{ $existingTickets }} ticket(s) for this journey. You can book {{ 5 - $existingTickets }} more.</small>
            @endif
        </div>

        <!-- Progress bar -->
        <div class="progress mb-4 progress-20">
            <div class="progress-bar bg-success w-33" role="progressbar">Step 1</div>
        </div>

        <form id="seatForm" method="POST" action="{{ route('booking.step2') }}">
            @csrf
            <!-- Class selection -->
            <div class="mb-3">
                <label for="class" class="form-label fw-bold">Select Class:</label>
                <select id="class" name="class" class="form-select w-50 mx-auto fw-bold" required>
                    <option selected disabled>Choose class</option>
                </select>
            </div>

            <!-- Compartment selection -->
            <div class="mb-4 hidden-init" id="compartment-section">
                <label for="compartment" class="form-label fw-bold">Select Compartment:</label>
                <select id="compartment" name="compartment_id" class="form-select w-50 mx-auto fw-bold" required>
                    <option selected disabled>Choose compartment</option>
                </select>
            </div>

            <div id="selected-seats-inputs"></div>

            <div id="seat-section" class="hidden-init">
                <div class="row justify-content-center align-items-start">
                    <div class="col-md-7">
                        <div class="card p-4 mb-4">
                            <div id="seat-layout" class="seat-layout"></div>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="col-md-3 text-start">
                        <h5 class="fw-bold">Legend:</h5>
                        <p><span class="legend-box available"></span> Available</p>
                        <p><span class="legend-box taken"></span> Taken</p>
                    </div>
                </div>

                <!-- Selected seats & price -->
                <h4 class="mt-4">Selected Seat(s): <span id="selected-seats">None</span></h4>
                <!-- @if(Auth::check() && $existingTickets > 0)
                    <p class="text-muted small">You can select {{ 5 - $existingTickets }} more seat(s)</p>
                @else
                    <p class="text-muted small">Maximum 5 seats per booking</p>
                @endif -->
                <h4>Total Price: <span id="total-price">৳ 0</span></h4>

                <button type="submit" class="btn btn-primary btn-sm mt-4 px-4 py-2 fw-bold mb-2 minw-50"
                    id="next-btn" disabled>Next</button>
            </div>
        </form>
    </div>

    <script>
        const seatPrice = 350;
        const selectedSeats = new Set();
        const takenSeats = @json($bookedSeats);
        const existingTickets = @json($existingTickets ?? 0);
        const maxSeatsAllowed = 5 - existingTickets;
        const compartments = @json($compartments);
        const seats = @json($seats);
        const ticketPrices = @json($ticketPrices);

        console.log('Compartments:', compartments);
        console.log('Seats:', seats);
        console.log('Taken seats:', takenSeats);

        const classDropdown = document.getElementById('class');
        const compartmentDropdown = document.getElementById('compartment');
        const seatSection = document.getElementById('seat-section');
        const compartmentSection = document.getElementById('compartment-section');
        const seatLayout = document.getElementById('seat-layout');

        document.addEventListener('DOMContentLoaded', () => {
            try {
                const classKeys = Object.keys(compartments || {});
                classKeys.forEach(k => {
                    const opt = document.createElement('option');
                    opt.value = k;
                    opt.textContent = k.charAt(0).toUpperCase() + k.slice(1);
                    classDropdown.appendChild(opt);
                });
            } catch (e) {
                console.error('Failed to populate classes', e);
            }
        });

        classDropdown.addEventListener('change', () => {
            const selectedClass = classDropdown.value;
            compartmentDropdown.innerHTML = '<option selected disabled>Choose compartment</option>';
            
            const comps = compartments[selectedClass] || [];
            comps.forEach(comp => {
                    const option = document.createElement('option');
                    option.value = comp.compartment_id;
                    option.textContent = comp.compartment_name;
                    compartmentDropdown.appendChild(option);
                });
            
            compartmentSection.style.display = 'block';
            seatSection.style.display = 'none';
        });

        compartmentDropdown.addEventListener('change', () => {
            seatSection.style.display = 'block';
            renderSeats(classDropdown.value, compartmentDropdown.value);
        });

        function renderSeats(trainClass, compartment) {
            seatLayout.innerHTML = '';
            selectedSeats.clear();
            updateSeatSummary();

            const selectedCompartmentId = parseInt(compartmentDropdown.value);
            const compartmentSeats = seats.filter(seat => seat.compartment_id === selectedCompartmentId);
            
            console.log('Selected compartment ID:', selectedCompartmentId);
            console.log('Filtered compartment seats:', compartmentSeats);
            
            let rows = 0;
            let seatsPerRow = [];

            if (trainClass.toLowerCase() === 'ac') {
                rows = 4; // 4 rows per compartment
                seatsPerRow = [1, 1]; // 1-1 layout
            } else if (trainClass.toLowerCase() === 'shovan') {
                rows = 6; // 6 rows per compartment  
                seatsPerRow = [1, 2]; // 1-2 layout
            } else if (trainClass.toLowerCase() === 'snigdha') {
                rows = 6; // 6 rows per compartment
                seatsPerRow = [2, 2]; // 2-2 layout
            }

            let seatIndex = 0;
            for (let row = 0; row < rows; row++) {
                const rowDiv = document.createElement('div');
                rowDiv.classList.add('d-flex', 'justify-content-center', 'mb-2', 'w-100');

                // Left side seats
                for (let i = 0; i < seatsPerRow[0]; i++) {
                    if (seatIndex < compartmentSeats.length) {
                        rowDiv.appendChild(createSeat(compartmentSeats[seatIndex]));
                        seatIndex++;
                    }
                }

                rowDiv.appendChild(createSpacer());

                // Right side seats
                for (let i = 0; i < seatsPerRow[1]; i++) {
                    if (seatIndex < compartmentSeats.length) {
                        rowDiv.appendChild(createSeat(compartmentSeats[seatIndex]));
                        seatIndex++;
                    }
                }

                seatLayout.appendChild(rowDiv);
            }

            attachSeatClickListeners();
        }

        function createSeat(seatData) {
            const seat = document.createElement('div');
            seat.classList.add('seat');
            seat.dataset.seatId = seatData.seat_id;
            seat.dataset.seatNumber = seatData.seat_number;
            seat.textContent = seatData.seat_number;
            
            if (!seatData.is_available || takenSeats.includes(seatData.seat_id)) {
                seat.classList.add('taken');
            }
            
            return seat;
        }

        function createSpacer() {
            const spacer = document.createElement('div');
            spacer.classList.add('ms-4');
            return spacer;
        }

        function attachSeatClickListeners() {
            document.querySelectorAll('.seat').forEach(seat => {
                seat.addEventListener('click', function () {
                    if (this.classList.contains('taken')) return;

                    const seatId = this.dataset.seatId;

                    if (this.classList.contains('selected')) {
                        this.classList.remove('selected');
                        selectedSeats.delete(seatId);
                    } else {
                        if (selectedSeats.size >= maxSeatsAllowed) {
                            const message = existingTickets > 0 
                                ? `You can only select ${maxSeatsAllowed} more seat(s). You already have ${existingTickets} ticket(s) for this journey.`
                                : 'You can only select maximum 5 seats per booking.';
                            alert(message);
                            return;
                        }
                        this.classList.add('selected');
                        selectedSeats.add(seatId);
                    }

                    updateSeatSummary();
                });
            });
        }

        function updateSeatSummary() {
            const seatNumbers = [...selectedSeats].map(seatId => {
                const seat = document.querySelector(`[data-seat-id="${seatId}"]`);
                return seat ? seat.dataset.seatNumber : '';
            }).filter(num => num);
            
            document.getElementById('selected-seats').textContent =
                seatNumbers.length ? seatNumbers.join(', ') : 'None';

            const selectedClass = classDropdown.value;
            const currentPrice = ticketPrices && ticketPrices[selectedClass] ? (ticketPrices[selectedClass].base_price || ticketPrices[selectedClass]) : seatPrice;
            
            document.getElementById('total-price').textContent =
                '৳ ' + (selectedSeats.size * currentPrice);

            const inputsContainer = document.getElementById('selected-seats-inputs');
            inputsContainer.innerHTML = '';
            selectedSeats.forEach(seatId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_seats[]';
                input.value = seatId;
                inputsContainer.appendChild(input);
            });

            document.getElementById('next-btn').disabled = selectedSeats.size === 0;
        }
    </script>
</body>

</html>