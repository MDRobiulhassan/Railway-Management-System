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
        'departure_time',
        'arrival_time',
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
        $totalSeats = $this->train->total_seats ?? 100;
        
        $bookedSeats = \App\Models\Ticket::where('train_id', $this->train_id)
            ->where('travel_date', $this->departure_time->format('Y-m-d'))
            ->whereIn('ticket_status', ['active'])
            ->count();
        
        return max(0, $totalSeats - $bookedSeats);
    }

    public function availableSeats()
    {
        return $this->getAvailableSeatsAttribute();
    }
}
