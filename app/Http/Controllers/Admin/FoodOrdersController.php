<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodOrder;
use App\Models\FoodItem;
use App\Models\Booking;
use Illuminate\Http\Request;

class FoodOrdersController extends Controller
{
    public function index()
    {
        $orders = FoodOrder::with(['booking.user', 'foodItem'])->orderByDesc('order_id')->paginate(20);
        $foodItems = FoodItem::orderBy('name')->get(['food_id', 'name', 'price']);
        $bookings = Booking::with('user')->orderByDesc('booking_id')->limit(100)->get(['booking_id', 'user_id']);
        return view('admin.food_order', compact('orders', 'foodItems', 'bookings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'exists:bookings,booking_id'],
            'food_id' => ['required', 'exists:food_items,food_id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);
        FoodOrder::create($validated);
        return redirect()->route('admin.food_order')->with('success', 'Food order added');
    }

    public function update(Request $request, FoodOrder $food_order)
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'exists:bookings,booking_id'],
            'food_id' => ['required', 'exists:food_items,food_id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);
        $food_order->update($validated);
        return redirect()->route('admin.food_order')->with('success', 'Food order updated');
    }

    public function destroy(FoodOrder $food_order)
    {
        $food_order->delete();
        return redirect()->route('admin.food_order')->with('success', 'Food order deleted');
    }
}


