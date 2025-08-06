<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Booking Confirm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f9f9f9;
            font-weight: bold;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
        }

        .confirm-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: #007bff;
            margin-bottom: 15px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .info-label {
            color: #555;
        }

        .dashboard-link {
            display: inline-block;
            margin-top: 30px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="confirm-card">
            <h3 class="text-center mb-4">Booking Confirmation</h3>

            <!-- Train Details -->
            <div>
                <h5 class="section-title">Train Details</h5>
                <div class="info-row">
                    <div class="info-label">Train Name:</div>
                    <div id="trainName">Express Line</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Departure:</div>
                    <div id="departureCity">Dhaka</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Arrival:</div>
                    <div id="arrivalCity">Chittagong</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Class:</div>
                    <div id="class">AC</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Date:</div>
                    <div id="travelDate">2025-08-10</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Time:</div>
                    <div id="travelTime">08:00 AM</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Number of Seats:</div>
                    <div id="seatnumbers">2</div>
                </div>
            </div>

            <!-- Food Info -->
            <div id="foodSection" style="margin-top: 30px; display: none;">
                <h5 class="section-title">Food Order</h5>
                <ul id="foodList" style="list-style-type: disc; padding-left: 20px;"></ul>
            </div>

            <!-- Payment & Total -->
            <div style="margin-top: 30px;">
                <h5 class="section-title">Payment</h5>
                <div class="info-row">
                    <div class="info-label">Total Payout:</div>
                    <div id="totalPayout">৳0</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Payment Method:</div>
                    <div id="paymentMethod">N/A</div>
                </div>
            </div>

            <a href="{{ route('userdashboard') }}"
                class="btn btn-primary mt-4 w-100 fw-bold dashboard-link">Proceed</a>
        </div>
    </div>

    <script>
        // Sample data 
        const bookingData = {
            trainName: "Express Line",
            departureCity: "Dhaka",
            arrivalCity: "Chittagong",
            travelDate: "2025-08-10",
            travelTime: "08:00 AM",
            foodOrder: [
                { name: "Chicken Biriyani", qty: 1 },
                { name: "Coca-Cola", qty: 2 }
            ],
            totalPayout: 800,
            paymentMethod: "bKash"
        };

        // Fill train details
        document.getElementById("trainName").textContent = bookingData.trainName;
        document.getElementById("departureCity").textContent = bookingData.departureCity;
        document.getElementById("arrivalCity").textContent = bookingData.arrivalCity;
        document.getElementById("travelDate").textContent = bookingData.travelDate;
        document.getElementById("travelTime").textContent = bookingData.travelTime;

        // Fill food info if available
        if (bookingData.foodOrder && bookingData.foodOrder.length > 0) {
            document.getElementById("foodSection").style.display = "block";
            const foodList = document.getElementById("foodList");
            bookingData.foodOrder.forEach(food => {
                const li = document.createElement("li");
                li.textContent = `${food.name} x${food.qty}`;
                foodList.appendChild(li);
            });
        }

        // Fill payment info
        document.getElementById("totalPayout").textContent = `৳${bookingData.totalPayout}`;
        document.getElementById("paymentMethod").textContent = bookingData.paymentMethod;
    </script>
</body>

</html>