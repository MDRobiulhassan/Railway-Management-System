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

    
    public function foodOrders()
    {
        return $this->hasMany(FoodOrder::class, 'food_id', 'food_id');
    }

    
    public function scopeAvailable($query)
    {
        return $query->where('availability', true);
    }

    
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    
    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        if (strpos($this->image, '/') !== false) {
            return asset('storage/' . ltrim($this->image, '/'));
        }

        return asset('images/' . $this->image);
    }
}
