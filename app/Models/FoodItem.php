<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'food_id';

    protected $fillable = [
        'name',
        'category',
        'description',
        'price',
        'availability',
    ];

    protected $casts = [
        'availability' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(FoodOrder::class, 'food_id', 'food_id');
    }
}


