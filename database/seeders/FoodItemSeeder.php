<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FoodItem;

class FoodItemSeeder extends Seeder
{
    public function run(): void
    {
        $foodItems = [
            [
                'name' => 'Beef Burger',
                'image' => 'beefburger.jpg',
                'category' => 'heavy_food',
                'description' => 'Juicy beef patty with fresh vegetables and sauce',
                'price' => 120.00,
                'availability' => true
            ],
            [
                'name' => 'Birani',
                'image' => 'birani.webp',
                'category' => 'heavy_food',
                'description' => 'Spiced rice cooked with meat and aromatic herbs',
                'price' => 100.00,
                'availability' => true
            ],
            [
                'name' => 'Chicken Noodles',
                'image' => 'chickennoodles.jpg',
                'category' => 'Snack',
                'description' => 'Stir-fried noodles with tender chicken and vegetables',
                'price' => 80.00,
                'availability' => true
            ],
            [
                'name' => 'Coca Cola',
                'image' => 'cocacola.jpg',
                'category' => 'Beverage',
                'description' => 'Refreshing carbonated soft drink',
                'price' => 30.00,
                'availability' => true
            ],
            [
                'name' => 'French Fries',
                'image' => 'frenchfries.jpg',
                'category' => 'Snack',
                'description' => 'Crispy golden potato fries',
                'price' => 60.00,
                'availability' => true
            ],
            [
                'name' => 'Orange Juice',
                'image' => 'orangejuice.jpg',
                'category' => 'Beverage',
                'description' => 'Freshly squeezed orange juice',
                'price' => 40.00,
                'availability' => true
            ]
        ];

        foreach ($foodItems as $item) {
            FoodItem::updateOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
    }
}
