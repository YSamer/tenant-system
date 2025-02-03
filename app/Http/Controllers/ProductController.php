<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use App\Models\Category;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('store', 'category')->paginate(15);
        return Inertia::render('Merchant/Products/Index', [
            'products' => $products
        ]);
    }

    public function create()
    {
        $stores = Store::all();
        $categories = Category::all();
        return Inertia::render('Merchant/Products/Create', [
            'stores' => $stores,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable',
        ]);

        Product::create($validated);

        return redirect()->route('merchant.products.index');
    }
}
