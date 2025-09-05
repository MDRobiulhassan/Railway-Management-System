<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $primaryKey = 'booking_id';
    
    protected $fillable = [
        'user_id',
        'train_id',
        'booking_date',
        'status',
        'total_amount'
    ];

    protected $casts = [
        'booking_date' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function train()
    {
        return $this->belongsTo(Train::class, 'train_id', 'train_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'booking_id', 'booking_id');
    }
}
