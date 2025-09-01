<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Step 2: Food Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/booking_step2.css') }}">
</head>

<body>

    <div class="container py-5">

        <h2 class="text-center fw-bold mb-3">Step 2: Food Order (Optional)</h2>

        <div class="progress mb-4" style="height: 20px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: 33%">Step 1</div>
        </div>

        <!-- Food categories -->
        @php
            $categories = [
                'Heavy Meals' => [
                    ['name' => 'Chicken Biriyani', 'desc' => 'Delicious chicken biriyani with salad.', 'price' => 150, 'img' => 'birani.webp'],
                    ['name' => 'Beef Burger', 'desc' => 'Juicy beef burger with fries.', 'price' => 120, 'img' => 'beefburger.jpg']
                ],
                'Snacks' => [
                    ['name' => 'Chicken Noodles', 'desc' => 'Spicy noodles with chicken and veggies.', 'price' => 100, 'img' => 'chickennoodles.jpg'],
                    ['name' => 'French Fries', 'desc' => 'Crispy fries with ketchup.', 'price' => 70, 'img' => 'frenchfries.jpg']
                ],
                'Drinks' => [
                    ['name' => 'Coca-Cola', 'desc' => 'Chilled soft drink bottle.', 'price' => 40, 'img' => 'cocacola.jpg'],
                    ['name' => 'Orange Juice', 'desc' => 'Freshly squeezed orange juice.', 'price' => 60, 'img' => 'orangejuice.jpg']
                ]
            ];
        @endphp

        @foreach($categories as $catName => $foods)
            <h4 class="text-primary category-heading">🍴 {{ $catName }}</h4>
            <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
                @foreach($foods as $index => $food)
                    <div class="col">
                        <div class="card h-100 text-center shadow">
                            <img src="{{ asset('images/' . $food['img']) }}" class="card-img-top" alt="{{ $food['name'] }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $food['name'] }}</h5>
                                <p class="card-text">{{ $food['desc'] }}</p>
                                <strong class="text-success">৳{{ $food['price'] }}</strong>
                                <div class="mt-2 d-flex justify-content-center align-items-center gap-2">
                                    <button class="btn btn-sm btn-outline-danger btn-decrease"
                                        data-price="{{ $food['price'] }}">-</button>
                                    <input type="number" min="0" value="0" class="quantity-input"
                                        data-price="{{ $food['price'] }}">
                                    <button class="btn btn-sm btn-outline-success btn-increase"
                                        data-price="{{ $food['price'] }}">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

        <!-- Total & Navigation -->
        <div class="mt-5 text-center">
            <h4>Total: <span id="totalPrice">৳0</span></h4>
            <div class="mt-4">
                <a href="{{ route('booking.step3') }}" class="btn btn-primary me-3 px-4 py-2 fw-bold">Next</a>
                <a href="{{ route('booking.step3') }}" class="btn btn-outline-secondary px-4 py-2 fw-bold">Skip</a>
            </div>
        </div>

    </div>

    <script>
        const totalPriceEl = document.getElementById('totalPrice');

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.quantity-input').forEach(input => {
                const qty = parseInt(input.value) || 0;
                const price = parseInt(input.dataset.price);
                total += qty * price;
            });
            totalPriceEl.textContent = `৳${total}`;
        }

        document.querySelectorAll('.btn-increase').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = btn.parentElement.querySelector('.quantity-input');
                input.value = parseInt(input.value) + 1;
                updateTotal();
            });
        });

        document.querySelectorAll('.btn-decrease').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = btn.parentElement.querySelector('.quantity-input');
                input.value = Math.max(0, parseInt(input.value) - 1);
                updateTotal();
            });
        });

        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', updateTotal);
        });
    </script>

</body>

</html>