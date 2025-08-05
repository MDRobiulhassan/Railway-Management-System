@extends('master')

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-4 font-weight-bold"> Railway Ticket</h2>

    <p class="text-center text-muted">
        To book  <strong>A ticket</strong> online, start by selecting your 
        <strong>departure station</strong> (From), <strong>destination station</strong> (To), 
        <strong>journey date</strong> (dd-mm-yyyy), and <strong>travel class</strong> (Class). 
        Then click <strong>Search Trains</strong>.
    </p>

    <div class="alert alert-warning text-center mt-3">
        This website is not affiliated with any government entity. Please read the disclaimer carefully before using the train search tool.
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('train.search') }}" class="border p-4 rounded shadow-sm bg-light">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="from">From</label>
                <select class="form-control" name="from" id="from">
                    <option selected disabled>Select a Station</option>
                    <option value="Dhaka">Dhaka</option>
                    <option value="Rajshahi">Rajshahi</option>
                    <option value="Chittagong">Chittagong</option>
                    <option value="Dinajpur">Dinajpur</option>
                    <option value="Khulna">Khulna</option>
                </select>
            </div>

            <div class="form-group col-md-3">
                <label for="to">To</label>
                <select class="form-control" name="to" id="to">
                    <option selected disabled>Select a Station</option>
                    <option value="Rajshahi">Rajshahi</option>
                    <option value="Dhaka">Dhaka</option>
                    <option value="Chittagong">Chittagong</option>
                    <option value="Dinajpur">Dinajpur</option>
                    <option value="Khulna">Khulna</option>
                </select>
            </div>

            <div class="form-group col-md-3">
                <label for="date">Date of Journey</label>
                <input type="date" class="form-control" name="date" id="date">
            </div>

            <div class="form-group col-md-3">
                <label for="class">Class</label>
                <select class="form-control" name="class" id="class">
                    <option selected disabled>Choose a Class</option>
                    <option value="Economy">Economy</option>
                    <option value="Business">Business</option>
                    <option value="Sleeper">Sleeper</option>
                </select>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary px-5">Search Trains</button>
        </div>
    </form>

    <!-- Results Section -->
    @if(request()->has('from') && request()->has('to'))
    <div class="mt-5">
        <h4 class="mb-3">Search Results:</h4>
        <div class="card shadow-sm p-3">
            <p><strong>Train:</strong> Padma Express</p>
            <p><strong>Route:</strong> {{ request('from') }} → {{ request('to') }}</p>
            <p><strong>Departure:</strong> 07:00 AM</p>
            <p><strong>Arrival:</strong> 12:00 PM</p>
            <p><strong>Class:</strong> {{ request('class') }}</p>
            <p><strong>Date:</strong> {{ request('date') }}</p>
            <p><strong>Status:</strong> Seats Available</p>
        </div>
    </div>
    @endif
</div>
@endsection
