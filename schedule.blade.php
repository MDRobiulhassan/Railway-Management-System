@extends('master')

@section('content')
<div class="container schedule-container mt-5">
    <h2 class="text-center mb-4 text-primary font-weight-bold">Train Schedule</h2>

    <div class="schedule-table border p-4 rounded shadow-sm">
        <div class="schedule-header d-flex justify-content-between font-weight-bold border-bottom pb-2 text-uppercase text-primary">
            <div class="w-25">Route</div>
            <div class="w-25">Train Name</div>
            <div class="w-25">Arrival Time</div>
            <div class="w-25">Departure Time</div>
        </div>

        <!-- Example Static Rows (replace with loop later) -->
        <div class="schedule-row d-flex justify-content-between py-3 border-bottom">
            <div class="w-25">Dhaka → Rajshahi</div>
            <div class="w-25">Padma Express</div>
            <div class="w-25">12:00 PM</div>
            <div class="w-25">07:00 AM</div>
        </div>
        <div class="schedule-row d-flex justify-content-between py-3 border-bottom">
            <div class="w-25">Dhaka → Chittagong</div>
            <div class="w-25">Suborno Express</div>
            <div class="w-25">10:30 PM</div>
            <div class="w-25">04:30 PM</div>
        </div>
        <div class="schedule-row d-flex justify-content-between py-3 border-bottom">
            <div class="w-25">Dhaka → Dinajpur</div>
            <div class="w-25">Ekota Express</div>
            <div class="w-25">07:00 AM</div>
            <div class="w-25">10:00 PM</div>
        </div>
    </div>
</div>
@endsection



