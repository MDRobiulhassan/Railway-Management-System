<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
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
            background: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: scaleIn 0.5s ease-out;
        }
        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }
        .icon-wrapper i {
            font-size: 50px;
            color: white;
        }
        .checkmark {
            animation: checkmark 0.5s ease-out 0.3s both;
        }
        @keyframes checkmark {
            from {
                stroke-dashoffset: 100;
            }
            to {
                stroke-dashoffset: 0;
            }
        }
        h2 {
            color: #28a745;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .spinner-border {
            width: 2rem;
            height: 2rem;
        }
    </style>
</head>
<body>
    <div class="payment-card">
        <div class="icon-wrapper">
            <i class="bi bi-check-circle"></i>
        </div>
        
        <h2>Payment Successful!</h2>
        <p class="text-muted mb-4">{{ $message ?? 'Your booking has been confirmed.' }}</p>
        
        @if(isset($bookingId))
        <div class="alert alert-success">
            <strong>Booking ID:</strong> #{{ $bookingId }}
        </div>
        @endif
        
        <div class="mb-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Redirecting to your dashboard...</p>
        </div>
        
        <p class="text-muted small">If you are not redirected automatically, 
            <a href="{{ route('user.dashboard') }}" class="text-decoration-none">click here</a>
        </p>
    </div>

    <script>
        // Auto-redirect to dashboard after 2 seconds
        setTimeout(function() {
            window.location.href = "{{ route('user.dashboard') }}";
        }, 2000);
    </script>
</body>
</html>
