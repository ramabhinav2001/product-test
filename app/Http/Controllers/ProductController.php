<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Show the product page
     */
    public function index()
    {
        return view('products');
    }

    /**
     * Return all products as JSON
     */
    public function list()
    {
        $products = $this->readProducts();

        // Order by datetime submitted (newest first)
        usort($products, function ($a, $b) {
            return strtotime($b['submitted_at']) <=> strtotime($a['submitted_at']);
        });

        return response()->json($products);
    }

    /**
     * Store a new product in JSON
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity'     => 'required|integer|min:0',
            'price'        => 'required|numeric|min:0',
        ]);

        $products = $this->readProducts();

        $products[] = [
            'id'           => (string) Str::uuid(),
            'product_name' => $validated['product_name'],
            'quantity'     => (int) $validated['quantity'],
            'price'        => (float) $validated['price'],
            'submitted_at' => now()->toDateTimeString(),
        ];

        Storage::put('products.json', json_encode($products, JSON_PRETTY_PRINT));

        return response()->json(['success' => true]);
    }

    /**
     * Read products from JSON file
     */
    private function readProducts(): array
    {
        if (!Storage::exists('products.json')) {
            return [];
        }

        return json_decode(Storage::get('products.json'), true) ?? [];
    }
}
