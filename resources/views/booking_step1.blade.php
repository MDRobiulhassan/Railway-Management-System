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

        <!-- Progress bar -->
        <div class="progress mb-4" style="height: 20px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: 33%">Step 1</div>
        </div>

        <!-- Class selection -->
        <div class="mb-3">
            <label for="class" class="form-label fw-bold">Select Class:</label>
            <select id="class" class="form-select w-50 mx-auto fw-bold">
                <option selected disabled>Choose class</option>
                <option value="ac">AC</option>
                <option value="shovan">Shovan</option>
                <option value="snigdha">Snigdha</option>
            </select>
        </div>

        <!-- Compartment selection -->
        <div class="mb-4" id="compartment-section" style="display: none;">
            <label for="compartment" class="form-label fw-bold">Select Compartment:</label>
            <select id="compartment" class="form-select w-50 mx-auto fw-bold">
                <option selected disabled>Choose compartment</option>
            </select>
        </div>

        <!-- Seat layout & legend -->
        <div id="seat-section" style="display: none;">
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
            <h4>Total Price: <span id="total-price">৳ 0</span></h4>

            <a href="{{ route('booking.step2') }}" class="btn btn-primary btn-sm mt-4 px-4 py-2 fw-bold mb-2"
                id="next-btn" style="min-width: 50px;">Next</a>
        </div>
    </div>

    <script>
        const seatPrice = 350;
        const selectedSeats = new Set();
        const takenSeats = [3, 5, 9, 16, 22];

        const classDropdown = document.getElementById('class');
        const compartmentDropdown = document.getElementById('compartment');
        const seatSection = document.getElementById('seat-section');
        const compartmentSection = document.getElementById('compartment-section');
        const seatLayout = document.getElementById('seat-layout');

        const compartmentOptions = {
            ac: ['Ka', 'Kha'],
            shovan: ['Ga', 'Gha'],
            snigdha: ['Uma', 'Cha']
        };

        classDropdown.addEventListener('change', () => {
            const selectedClass = classDropdown.value;
            compartmentDropdown.innerHTML = '<option selected disabled>Choose compartment</option>';
            compartmentOptions[selectedClass].forEach(opt => {
                const option = document.createElement('option');
                option.value = opt.toLowerCase();
                option.textContent = opt;
                compartmentDropdown.appendChild(option);
            });
            compartmentSection.style.display = 'block';
            seatSection.style.display = 'none';
        });

        compartmentDropdown.addEventListener('change', () => {
            seatSection.style.display = 'block';
            renderSeats(classDropdown.value);
        });

        function renderSeats(trainClass, compartment) {
            seatLayout.innerHTML = '';
            selectedSeats.clear();
            updateSeatSummary();

            let seatNumberStart = 1;
            let seatNumberEnd = 0;
            let rows = 0;

            if (trainClass === 'ac') {
                rows = 4; // 4 rows per compartment
                seatNumberStart = compartment === 'ka' ? 1 : 9;
                seatNumberEnd = compartment === 'ka' ? 8 : 16;
            } else if (trainClass === 'shovan') {
                rows = 6; // 6 rows per compartment
                seatNumberStart = compartment === 'ga' ? 1 : 19;
                seatNumberEnd = compartment === 'ga' ? 18 : 36;
            } else if (trainClass === 'snigdha') {
                rows = 6; // 6 rows per compartment
                seatNumberStart = compartment === 'uma' ? 1 : 25;
                seatNumberEnd = compartment === 'uma' ? 24 : 48;
            }

            let seatNumber = seatNumberStart;

            for (let row = 0; row < rows; row++) {
                const rowDiv = document.createElement('div');
                rowDiv.classList.add('d-flex', 'justify-content-center', 'mb-2', 'w-100');

                if (trainClass === 'ac') {
                    // 1-1 layout
                    rowDiv.appendChild(createSeat(seatNumber++));
                    rowDiv.appendChild(createSpacer());
                    rowDiv.appendChild(createSeat(seatNumber++));
                } else if (trainClass === 'shovan') {
                    // 1-2 layout
                    rowDiv.appendChild(createSeat(seatNumber++));
                    rowDiv.appendChild(createSeat(seatNumber++));
                    rowDiv.appendChild(createSpacer());
                    rowDiv.appendChild(createSeat(seatNumber++));
                } else if (trainClass === 'snigdha') {
                    // 2-2 layout
                    rowDiv.appendChild(createSeat(seatNumber++));
                    rowDiv.appendChild(createSeat(seatNumber++));
                    rowDiv.appendChild(createSpacer());
                    rowDiv.appendChild(createSeat(seatNumber++));
                    rowDiv.appendChild(createSeat(seatNumber++));
                }

                seatLayout.appendChild(rowDiv);
            }

            attachSeatClickListeners();
        }

        // Compartment change listener
        compartmentDropdown.addEventListener('change', () => {
            seatSection.style.display = 'block';
            renderSeats(classDropdown.value, compartmentDropdown.value);
        });



        function createSeat(num) {
            const seat = document.createElement('div');
            seat.classList.add('seat');
            seat.dataset.seat = num;
            seat.textContent = num;
            if (takenSeats.includes(num)) seat.classList.add('taken');
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

                    const seatNum = this.dataset.seat;

                    if (this.classList.contains('selected')) {
                        this.classList.remove('selected');
                        selectedSeats.delete(seatNum);
                    } else {
                        this.classList.add('selected');
                        selectedSeats.add(seatNum);
                    }

                    updateSeatSummary();
                });
            });
        }

        function updateSeatSummary() {
            document.getElementById('selected-seats').textContent =
                selectedSeats.size ? [...selectedSeats].join(', ') : 'None';

            document.getElementById('total-price').textContent =
                '৳ ' + (selectedSeats.size * seatPrice);
        }
    </script>
</body>

</html>