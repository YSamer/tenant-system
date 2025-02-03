<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Facades\Tenants as FacadesTenants;

// Route::domain('{subdomain}.' . env('APP_HOST'))->group(function () {
//     Route::middleware('auth')->get('/dashboard', function () {
//         return view('dashboard');
//     });
// });

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function (Request $request) {
    $user = $request->user();
    if ($user && $user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    // if ($user && $user->tenant) {
    //     $domain = $user->tenant->domain;

    //     // If domain is just a hostname, prepend it with https:// (or any other scheme)
    //     if (!preg_match('/^http[s]?:\/\//', $domain)) {
    //         $domain = 'https://' . $domain;
    //     }

    //     return redirect()->to($domain);
    // }

    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'tenant'])->name('dashboard');

Route::get('/admin/dashboard', function () {
    return Inertia::render('AdminDashboard');
})->middleware(['auth', 'verified', 'admin'])->name('admin.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
});

Route::middleware(['auth', 'tenant'])->prefix('merchant')->group(function () {
    // Store
    Route::get('/store-list', [StoreController::class, 'index'])->name('merchant.stores.index');
    Route::get('/create-store', [StoreController::class, 'create'])->name('merchant.stores.create');
    Route::post('/store-store', [StoreController::class, 'store'])->name('merchant.stores.store');

    // Category
    Route::get('/category-list', [CategoryController::class, 'index'])->name('merchant.categories.index');
    Route::get('/create-category', [CategoryController::class, 'create'])->name('merchant.categories.create');
    Route::post('/category-store', [CategoryController::class, 'store'])->name('merchant.categories.store');

    // Product
    Route::get('/product-list', [ProductController::class, 'index'])->name('merchant.products.index');
    Route::get('/create-product', [ProductController::class, 'create'])->name('merchant.products.create');
    Route::post('/product-store', [ProductController::class, 'store'])->name('merchant.products.store');
});


require __DIR__ . '/auth.php';
