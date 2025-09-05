<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $primaryKey = 'station_id';
    
    protected $fillable = [
        'name',
        'location'
    ];

    public function sourceSchedules()
    {
        return $this->hasMany(Schedule::class, 'source_id', 'station_id');
    }

    public function destinationSchedules()
    {
        return $this->hasMany(Schedule::class, 'destination_id', 'station_id');
    }
}
