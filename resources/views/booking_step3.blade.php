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
        <h3 class="text-center mb-3">Step 3: Payment Method</h3>
        <!-- Progress bar -->
        <div class="progress mb-4" style="height: 20px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: 100%">Step 3</div>
        </div>

        <div class="payment-card" onclick="selectPayment(this)">
            <img src="{{ asset('images/bkash.jpg') }}" alt="bKash" />
            <div>bKash</div>
            <input type="radio" name="payment" class="d-none" value="bKash" />
        </div>

        <div class="payment-card" onclick="selectPayment(this)">
            <img src="images/nagad.png" alt="Nagad" />
            <div>Nagad</div>
            <input type="radio" name="payment" class="d-none" value="Nagad" />
        </div>

        <div class="payment-card" onclick="selectPayment(this)">
            <img src="images/mastercard.png" alt="Card" />
            <div>Card (Visa/MasterCard)</div>
            <input type="radio" name="payment" class="d-none" value="Card" />
        </div>

        <a href="{{ route('booking.confirm') }}" class="btn btn-success btn-confirm mt-3">Confirm Purchase</a>
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