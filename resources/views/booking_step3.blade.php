<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Step 3: Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/booking_step3.css') }}">
</head>

<body>
    <div class="container">
        <form method="POST" action="{{ route('booking.confirm') }}">
            @csrf
            
            <h3 class="text-center mb-3">Step 3: Payment Method</h3>
            <div class="progress mb-4 progress-20">
                <div class="progress-bar bg-success w-100" role="progressbar">Step 3</div>
            </div>

            <div class="payment-card" onclick="selectPayment(this)">
                <img src="{{ asset('images/bkash.jpg') }}" alt="bKash" />
                <div>bKash</div>
                <input type="radio" name="payment_method" class="d-none" value="bKash" required />
            </div>

            <div class="payment-card" onclick="selectPayment(this)">
                <img src="{{ asset('images/nagad.png') }}" alt="Nagad" />
                <div>Nagad</div>
                <input type="radio" name="payment_method" class="d-none" value="Nagad" required />
            </div>

            <div class="payment-card" onclick="selectPayment(this)">
                <img src="{{ asset('images/mastercard.png') }}" alt="Card" />
                <div>Card (Visa/MasterCard)</div>
                <input type="radio" name="payment_method" class="d-none" value="Card" required />
            </div>

            <button type="submit" class="btn btn-success btn-confirm mt-3">Confirm Purchase</button>
        </form>
    </div>

    <script>
        function selectPayment(card) {
            document.querySelectorAll('.payment-card').forEach(c => {
                c.classList.remove('selected');
                c.querySelector('input[type=radio]').checked = false;
            });

            card.classList.add('selected');
            card.querySelector('input[type=radio]').checked = true;
        }
    </script>
</body>

</html>