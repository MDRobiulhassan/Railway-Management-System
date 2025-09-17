<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
</head>

<body>

    <x-navbar />


    <div class="container mt-5">
        <h2 class="mb-4 text-center fw-bold">Book Your Tickets</h2>
        <div class="search-card mx-auto maxw-900">
            <form action="{{ route('search.result') }}" method="GET">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="from" class="form-label fw-bold">From</label>
                        <select name="from" id="from" class="form-select fw-bold" required>
                            <option value="" disabled selected>Departure City</option>
                            @if(isset($stations))
                                @foreach($stations as $station)
                                    <option value="{{ $station->name }}">{{ $station->name }}</option>
                                @endforeach
                            @else
                                <option value="Dhaka">Dhaka</option>
                                <option value="Chittagong">Chittagong</option>
                                <option value="Rajshahi">Rajshahi</option>
                                <option value="Khulna">Khulna</option>
                                <option value="Sylhet">Sylhet</option>
                                <option value="Rangpur">Rangpur</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="to" class="form-label fw-bold">To</label>
                        <select name="to" id="to" class="form-select fw-bold" required>
                            <option value="" disabled selected>Arrival City</option>
                            @if(isset($stations))
                                @foreach($stations as $station)
                                    <option value="{{ $station->name }}">{{ $station->name }}</option>
                                @endforeach
                            @else
                                <option value="Dhaka">Dhaka</option>
                                <option value="Chittagong">Chittagong</option>
                                <option value="Rajshahi">Rajshahi</option>
                                <option value="Khulna">Khulna</option>
                                <option value="Sylhet">Sylhet</option>
                                <option value="Rangpur">Rangpur</option>
                            @endif
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="class" class="form-label fw-bold">Class</label>
                        <select name="class" id="class" class="form-select fw-bold">
                            <option value="" selected disabled>Choose</option>
                            <option value="ac">AC</option>
                            <option value="shovan">Shovan</option>
                            <option value="snigdha">Snigdha</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="date" class="form-label fw-bold">Date</label>
                        <input type="date" name="date" id="date" class="form-control fw-bold" min="{{ date('Y-m-d') }}" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary px-5 fw-bold">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>