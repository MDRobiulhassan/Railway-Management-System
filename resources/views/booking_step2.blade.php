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
        <form method="POST" action="{{ route('booking.confirm') }}">
            @csrf

            <h2 class="text-center fw-bold mb-3">Step 2: Food Order (Optional)</h2>

            <div class="progress mb-4 progress-20">
                <div class="progress-bar bg-success w-100" role="progressbar">Step 2</div>
            </div>

            @if($foodItems && $foodItems->count() > 0)
                @foreach($foodItems as $category => $foods)
                    <h4 class="text-primary category-heading">🍴 {{ $category }}</h4>
                    <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
                        @foreach($foods as $food)
                            <div class="col">
                                <div class="card h-100 text-center shadow">
                                    @if($food->image)
                                        <img src="{{ asset('images/' . $food->image) }}" class="card-img-top" alt="{{ $food->name }}">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $food->name }}</h5>
                                        <p class="card-text">{{ $food->description }}</p>
                                        <strong class="text-success">৳{{ $food->price }}</strong>
                                        <div class="mt-2 d-flex justify-content-center align-items-center gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-decrease"
                                                data-price="{{ $food->price }}">-</button>
                                            <input type="number" min="0" value="0" class="quantity-input"
                                                name="food_items[{{ $food->food_id }}]" data-price="{{ $food->price }}">
                                            <button type="button" class="btn btn-sm btn-outline-success btn-increase"
                                                data-price="{{ $food->price }}">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <div class="alert alert-info text-center">
                    <h5>No food items available at the moment</h5>
                    <p>You can skip this step and proceed to payment.</p>
                </div>
            @endif

            <div class="mt-5 text-center">
                <h4>Total: <span id="totalPrice">৳0</span></h4>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-3 px-4 py-2 fw-bold">Next</button>
                    <button type="submit" class="btn btn-outline-secondary px-4 py-2 fw-bold">Skip</button>
                </div>
            </div>
        </form>

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