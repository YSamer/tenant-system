<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserAuthService
{
    public function registerUser(array $data): ?User
    {
        try {
            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);
        } catch (\Exception $e) {
            Log::error('Failed to register user', ['error' => $e->getMessage()]);
            return null;
        }

        return $user;
    }

    public function loginUser(array $data): ?User
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return null;
        }

        Auth::login($user, true);

        return $user;
    }

    public function logout(): bool
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            Log::warning('Logout attempt without authenticated user.');
            return false;
        }
        $user->tokens()->delete();

        return true;
    }
}