<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function users()
    {
        $users = User::paginate(15);
        return Inertia::render('Admin/Users/Index', ['users' => $users]);
    }
}
