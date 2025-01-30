<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserDeleteAccountRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateProfileRequest;
use App\Http\Requests\UserVerifyPhoneRequest;
use App\Http\Resources\UserResource;
use App\Models\Note;
use App\Services\TenantService;
use Illuminate\Http\Request;
use App\Services\UserAuthService;
use Illuminate\Support\Facades\DB;

class UserAuthController extends Controller
{
    private UserAuthService $userAuthService;
    private TenantService $tenantService;
    public function __construct(UserAuthService $userAuthService, TenantService $tenantService)
    {
        $this->userAuthService = $userAuthService;
        $this->tenantService = $tenantService;
    }

    public function register(UserRegisterRequest $request)
    {
        $data = $request->validated();
        
        DB::beginTransaction();
        try {
            $user = $this->userAuthService->registerUser($data);
            if (!$user) {
                DB::rollback();
                return $this->errorResponse('Register Faild', 500);
            }
            $token = $user->createToken('auth_token')->plainTextToken;

            $tenant = $this->tenantService->createTenant(
                $user->id,
                $data['tenant_name'],
                $data['tenant_domain'],
                $data['tenant_database'],
            );

            if (!$tenant) {
                DB::rollback();
                return $this->errorResponse('Failed to create tenant', 500);
            }

            Note::create([
                'username' => $user->name,
                'content' => 'Test note for ' . $user->name,
            ]);

            DB::commit();
            return $this->successResponse(
                [
                    'user' => new UserResource($user),
                    'token' => $token,
                ],
                'Register successfully',
            );
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse('Failed to register', 500);
        }
    }

    public function login(UserLoginRequest $request)
    {

        $data = $request->validated();

        $user = $this->userAuthService->loginUser($data);
        if (!$user) {
            return $this->errorResponse('Login faild', 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse(
            ['user' => new UserResource($user), 'token' => $token],
            'Login successfully',
        );
    }

    public function user(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return $this->successResponse(
                null,
                'Auth faild',
            );
        }
        return $this->successResponse(
            ['user' => new UserResource($user)],
            'Auth successfully',
        );
    }

    public function logout(Request $request)
    {
        $this->userAuthService->logout();

        return $this->successResponse(
            null,
            'Logout successfully',
        );
    }
}
