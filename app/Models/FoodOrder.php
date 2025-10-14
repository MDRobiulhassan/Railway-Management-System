<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodOrder extends Model
{
    use HasFactory;

    protected $table = 'food_orders';
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'booking_id',
        'food_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    
    public function foodItem()
    {
        return $this->belongsTo(FoodItem::class, 'food_id', 'food_id');
    }

   
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->foodItem->price;
    }
}
