<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $primaryKey = 'schedule_id';
    
    protected $fillable = [
        'train_id',
        'source_id',
        'destination_id',
        'arrival_time',
        'departure_time'
    ];

    protected $casts = [
        'arrival_time' => 'datetime',
        'departure_time' => 'datetime'
    ];

    public function train()
    {
        return $this->belongsTo(Train::class, 'train_id', 'train_id');
    }

    public function sourceStation()
    {
        return $this->belongsTo(Station::class, 'source_id', 'station_id');
    }

    public function destinationStation()
    {
        return $this->belongsTo(Station::class, 'destination_id', 'station_id');
    }

    public function getDurationAttribute()
    {
        return $this->departure_time->diffInMinutes($this->arrival_time);
    }

    public function getFormattedDurationAttribute()
    {
        $minutes = $this->duration;
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        return $hours . 'h ' . $mins . 'm';
    }

    public function getAvailableSeatsAttribute()
    {
        $totalSeats = $this->train->total_seats ?? 0;
        
        // Count booked seats for this specific train (since bookings table doesn't have schedule_id)
        $bookedSeats = \App\Models\Booking::where('train_id', $this->train_id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->count();
        
        return max(0, $totalSeats - $bookedSeats);
    }
}
