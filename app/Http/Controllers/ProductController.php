<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    
    public function index()
    {
        return view('products');
    }

   
    public function list()
    {
        $products = $this->readProducts();

        
        usort($products, function ($a, $b) {
            return strtotime($b['submitted_at']) <=> strtotime($a['submitted_at']);
        });

        return response()->json($products);
    }

    
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

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity'     => 'required|integer|min:0',
            'price'        => 'required|numeric|min:0',
        ]);

        $products = $this->readProducts();

        foreach ($products as &$product) {
            if ($product['id'] === $id) {
                $product['product_name'] = $validated['product_name'];
                $product['quantity']     = (int) $validated['quantity'];
                $product['price']        = (float) $validated['price'];
                break;
            }
        }

        Storage::put('products.json', json_encode($products, JSON_PRETTY_PRINT));

        return response()->json(['success' => true]);
    }
    
    public function destroy($id)
    {
        $products = $this->readProducts();

        $products = array_values(array_filter($products, function ($product) use ($id) {
            return $product['id'] !== $id;
        }));

        Storage::put('products.json', json_encode($products, JSON_PRETTY_PRINT));

        return response()->json(['success' => true]);
    }
    
    private function readProducts(): array
    {
        if (!Storage::exists('products.json')) {
            return [];
        }

        return json_decode(Storage::get('products.json'), true) ?? [];
    }

}
