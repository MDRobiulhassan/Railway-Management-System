<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $primaryKey = 'ticket_id';

    protected $fillable = [
        'booking_id',
        'train_id',
        'seat_id',
        'travel_date',
        'compartment_id',
        'ticket_status',
    ];

    protected $casts = [
        'travel_date' => 'date',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function train()
    {
        return $this->belongsTo(Train::class, 'train_id', 'train_id');
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id', 'seat_id');
    }

    public function compartment()
    {
        return $this->belongsTo(Compartment::class, 'compartment_id', 'compartment_id');
    }
}


