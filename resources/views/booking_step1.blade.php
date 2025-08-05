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
        <div class="mb-4">
            <label for="class" class="form-label fw-bold">Select Class:</label>
            <select id="class" class="form-select w-50 mx-auto fw-bold">
                <option selected disabled>Choose class</option>
                <option value="ac">AC</option>
                <option value="shovan">Shovan</option>
                <option value="snigdha">Snigdha</option>
            </select>
        </div>

        <!-- Seat layout & legend side-by-side -->
        <div class="row justify-content-center align-items-start">
            <!-- Seat layout card -->
            <div class="col-md-7">
                <div class="card p-4 mb-4">
                    <div class="seat-layout">
                        @for ($i = 1; $i <= 32; $i++)
                            @php
                                $posInRow = ($i - 1) % 4; // 0,1,2,3
                                $isTaken = in_array($i, [3, 5, 9, 16, 22]);
                            @endphp

                            @if ($posInRow === 0)
                                <div class="d-flex justify-content-center mb-2 w-100">
                            @endif

                                <div class="seat {{ $isTaken ? 'taken' : '' }} {{ $posInRow === 2 ? 'ms-4' : '' }}"
                                    data-seat="{{ $i }}">
                                    {{ $i }}
                                </div>

                                @if ($posInRow === 3)
                                    </div>
                                @endif
                        @endfor
                    </div>
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

        <a href="{{ route('booking.step2') }}" class="btn btn-primary btn-sm mt-4 px-4 py-2 fw-bold mb-2" id="next-btn"
            style="min-width: 50px;">Next</a>

    </div>

    <script>
        const seatPrice = 350;
        const selectedSeats = new Set();

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

                document.getElementById('selected-seats').textContent =
                    selectedSeats.size ? [...selectedSeats].join(', ') : 'None';

                document.getElementById('total-price').textContent =
                    '৳ ' + (selectedSeats.size * seatPrice);
            });
        });
    </script>
</body>

</html>