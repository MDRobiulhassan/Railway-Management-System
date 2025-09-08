<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $primaryKey = 'seat_id';
    
    protected $fillable = [
        'train_id',
        'compartment_id',
        'seat_number',
        'is_available'
    ];

    protected $casts = [
        'is_available' => 'boolean'
    ];

    public function train()
    {
        return $this->belongsTo(Train::class, 'train_id', 'train_id');
    }

    public function compartment()
    {
        return $this->belongsTo(Compartment::class, 'compartment_id', 'compartment_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'seat_id', 'seat_id');
    }
}
