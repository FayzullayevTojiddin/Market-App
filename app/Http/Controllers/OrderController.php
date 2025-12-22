<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Product;

class OrderController extends Controller
{
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        
        return view('orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'status' => 'required|in:new,paid,cancelled',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.count' => 'required|integer|min:1',
            'products.*.discount' => 'nullable|numeric|min:0|max:100',
            'products.*.price_summ' => 'required|numeric|min:0',
        ]);

        $order = Order::create([
            'customer_id' => $validated['customer_id'],
            'status' => $validated['status'],
        ]);

        foreach ($validated['products'] as $product) {
            $order->products()->create($product);
        }

        return redirect()->route('orders.index')
            ->with('success', 'Buyurtma muvaffaqiyatli yaratildi!');
    }
}
