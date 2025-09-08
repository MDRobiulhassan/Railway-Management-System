<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodItem;
use Illuminate\Http\Request;

class FoodItemsController extends Controller
{
    public function index()
    {
        $foodItems = FoodItem::orderByDesc('food_id')->paginate(20);
        return view('admin.food_items', compact('foodItems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'availability' => ['required', 'boolean'],
        ]);
        FoodItem::create($validated);
        return redirect()->route('admin.food_items')->with('success', 'Food item added');
    }

    public function update(Request $request, FoodItem $food_item)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'availability' => ['required', 'boolean'],
        ]);
        $food_item->update($validated);
        return redirect()->route('admin.food_items')->with('success', 'Food item updated');
    }

    public function destroy(FoodItem $food_item)
    {
        $food_item->delete();
        return redirect()->route('admin.food_items')->with('success', 'Food item deleted');
    }
}


