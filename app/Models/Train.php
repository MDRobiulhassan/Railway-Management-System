<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    use HasFactory;

    protected $primaryKey = 'train_id';
    
    protected $fillable = [
        'train_name',
        'train_type',
        'total_seats'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'train_id', 'train_id');
    }

    public function compartments()
    {
        return $this->hasMany(Compartment::class, 'train_id', 'train_id');
    }

    public function ticketPrices()
    {
        return $this->hasMany(TicketPrice::class, 'train_id', 'train_id');
    }
}
