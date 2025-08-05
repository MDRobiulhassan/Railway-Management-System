<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Step 2: Food Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/booking_step2.css') }}">
</head>

<body style="background-color: #f9f9f9;">

    <div class="container py-5">

        <!-- Header -->
        <h2 class="text-center fw-bold mb-3">Step 2: Food Order (Optional)</h2>
        <!-- Progress bar -->
        <div class="progress mb-4" style="height: 20px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: 33%">Step 1</div>
        </div>

        <!-- Category: Heavy Meals -->
        <h4 class="text-primary category-heading">🍽️ Heavy Meals</h4>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @php
                $foods = [
                    ['name' => 'Chicken Biriyani', 'desc' => 'Delicious chicken biriyani with salad.', 'price' => 150, 'img' => 'birani.webp'],
                    ['name' => 'Beef Burger', 'desc' => 'Juicy beef burger with fries.', 'price' => 120, 'img' => 'beefburger.jpg']
                ];
            @endphp
            @foreach ($foods as $index => $food)
                <div class="col">
                    <div class="card h-100 text-center shadow">
                        <img src="{{ asset('images/' . $food['img']) }}" class="card-img-top" alt="{{ $food['name'] }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $food['name'] }}</h5>
                            <p class="card-text">{{ $food['desc'] }}</p>
                            <strong class="text-success">৳{{ $food['price'] }}</strong><br>
                            <button class="btn btn-sm btn-outline-primary mt-2 btn-toggle"
                                data-price="{{ $food['price'] }}">Add</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Category: Snacks -->
        <h4 class="text-primary category-heading">🍟 Snacks</h4>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @php
                $snacks = [
                    ['name' => 'Chicken Noodles', 'desc' => 'Spicy noodles with chicken and veggies.', 'price' => 100, 'img' => 'chickennoodles.jpg'],
                    ['name' => 'French Fries', 'desc' => 'Crispy fries with ketchup.', 'price' => 70, 'img' => 'frenchfries.jpg']
                ];
            @endphp
            @foreach ($snacks as $food)
                <div class="col">
                    <div class="card h-100 text-center shadow">
                        <img src="{{ asset('images/' . $food['img']) }}" class="card-img-top" alt="{{ $food['name'] }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $food['name'] }}</h5>
                            <p class="card-text">{{ $food['desc'] }}</p>
                            <strong class="text-success">৳{{ $food['price'] }}</strong><br>
                            <button class="btn btn-sm btn-outline-primary mt-2 btn-toggle"
                                data-price="{{ $food['price'] }}">Add</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Category: Drinks -->
        <h4 class="text-primary category-heading">🥤 Drinks</h4>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @php
                $drinks = [
                    ['name' => 'Coca-Cola', 'desc' => 'Chilled soft drink bottle.', 'price' => 40, 'img' => 'cocacola.jpg'],
                    ['name' => 'Orange Juice', 'desc' => 'Freshly squeezed orange juice.', 'price' => 60, 'img' => 'orangejuice.jpg']
                ];
            @endphp
            @foreach ($drinks as $food)
                <div class="col">
                    <div class="card h-100 text-center shadow">
                        <img src="{{ asset('images/' . $food['img']) }}" class="card-img-top" alt="{{ $food['name'] }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $food['name'] }}</h5>
                            <p class="card-text">{{ $food['desc'] }}</p>
                            <strong class="text-success">৳{{ $food['price'] }}</strong><br>
                            <button class="btn btn-sm btn-outline-primary mt-2 btn-toggle"
                                data-price="{{ $food['price'] }}">Add</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Total & Navigation -->
        <div class="mt-5 text-center">
            <h4>Total: <span id="totalPrice">৳0</span></h4>
            <div class="mt-4">
                <a href="{{ route('booking.step3') }}" class="btn btn-primary me-3 px-4 py-2 fw-bold">Next</a>
                <a href="{{ route('booking.step3') }}" class="btn btn-outline-secondary px-4 py-2 fw-bold">Skip</a>
            </div>
        </div>

    </div>

    <!-- JavaScript to handle add/remove -->
    <script>
        let total = 0;
        const priceDisplay = document.getElementById('totalPrice');

        document.querySelectorAll('.btn-toggle').forEach(button => {
            button.addEventListener('click', () => {
                const price = parseInt(button.dataset.price);
                const isAdded = button.classList.contains('btn-success');

                if (isAdded) {
                    total -= price;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-outline-primary');
                    button.textContent = 'Add';
                } else {
                    total += price;
                    button.classList.remove('btn-outline-primary');
                    button.classList.add('btn-success');
                    button.textContent = 'Remove';
                }

                priceDisplay.textContent = `৳${total}`;
            });
        });
    </script>

</body>

</html>