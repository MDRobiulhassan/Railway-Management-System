<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compartment extends Model
{
    use HasFactory;

    protected $primaryKey = 'compartment_id';

    protected $fillable = [
        'train_id',
        'compartment_name',
        'class_name',
    ];

    public function train()
    {
        return $this->belongsTo(Train::class, 'train_id', 'train_id');
    }

    public function seats()
    {
        return $this->hasMany(Seat::class, 'compartment_id', 'compartment_id');
    }
}


