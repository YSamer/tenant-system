<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Inertia\Inertia;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::paginate(15);
        return Inertia::render('Merchant/Stores/Index', [
            'stores' => $stores
        ]);
    }

    public function create()
    {
        return Inertia::render('Merchant/Stores/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:stores|max:255',
        ]);

        Store::create($validated);

        return redirect()->route('merchant.stores.index');
    }
}
