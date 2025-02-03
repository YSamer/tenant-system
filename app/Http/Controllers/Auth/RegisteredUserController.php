<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Services\TenantService;
use App\Services\UserAuthService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
class RegisteredUserController extends Controller
{
    private UserAuthService $userAuthService;
    private TenantService $tenantService;

    public function __construct(UserAuthService $userAuthService, TenantService $tenantService)
    {
        $this->userAuthService = $userAuthService;
        $this->tenantService = $tenantService;
    }
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserRegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $user = $this->userAuthService->registerUser($data);
            if (!$user) {
                DB::rollback();
                return redirect(route('login', absolute: false));
            }
            // $token = $user->createToken('auth_token')->plainTextToken;

            $tenant = $this->tenantService->createTenant(
                $user->id,
                $data['shop_name'],
            );

            event(new Registered($user));

            Auth::login($user);
            if (!$tenant) {
                DB::rollback();
                return redirect(route('login', absolute: false));
            }

            DB::commit();

            return redirect(route('dashboard', absolute: false));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('login', absolute: false));
        }
    }
}
