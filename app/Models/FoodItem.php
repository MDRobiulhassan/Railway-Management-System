<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    use HasFactory;

    protected $table = 'food_items';
    protected $primaryKey = 'food_id';

    protected $fillable = [
        'name',
        'image',
        'category',
        'description',
        'price',
        'availability',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'availability' => 'boolean',
    ];

    /**
     * Get the food orders for this food item
     */
    public function foodOrders()
    {
        return $this->hasMany(FoodOrder::class, 'food_id', 'food_id');
    }

    /**
     * Scope to get only available food items
     */
    public function scopeAvailable($query)
    {
        return $query->where('availability', true);
    }

    /**
     * Scope to filter by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
