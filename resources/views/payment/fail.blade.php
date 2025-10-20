<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .payment-card {
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 500px;
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .icon-wrapper {
            width: 100px;
            height: 100px;
            background: #dc3545;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        .icon-wrapper i {
            font-size: 50px;
            color: white;
        }
        h2 {
            color: #dc3545;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .transaction-id {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            font-family: monospace;
        }
        .btn-custom {
            padding: 12px 40px;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="payment-card">
        <div class="icon-wrapper">
            <i class="bi bi-x-circle"></i>
        </div>
        
        <h2>Payment Failed</h2>
        <p class="text-muted mb-4">{{ $message }}</p>
        
        @if(isset($transactionId))
        <div class="transaction-id">
            <small class="text-muted">Transaction ID:</small><br>
            <strong>{{ $transactionId }}</strong>
        </div>
        @endif
        
        <p class="mt-4">Your booking has not been confirmed. Please try again or contact support if the issue persists.</p>
        
        <div class="mt-4">
            <a href="{{ route('booking.confirm.get') }}" class="btn btn-danger btn-custom me-2">
                <i class="bi bi-arrow-repeat"></i> Try Again
            </a>
            <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-custom">
                <i class="bi bi-house"></i> Dashboard
            </a>
        </div>
    </div>
</body>
</html>
