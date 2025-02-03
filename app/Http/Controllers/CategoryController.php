<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Store;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('store')->paginate(15);
        return Inertia::render('Merchant/Categories/Index', [
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $stores = Store::all();
        return Inertia::render('Merchant/Categories/Create', [
            'stores' => $stores
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'name' => 'required|max:255',
        ]);

        Category::create($validated);

        return redirect()->route('merchant.categories.index');
    }
}

