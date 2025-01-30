<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Facades\Tenants as FacadesTenants;
use App\Models\Tenant;
use Illuminate\Support\Facades\Cache;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $auth = ''): Response
    {
        $host = $request->getHost();
        $user = $request->user();

        $tenant = Cache::remember("tenant_{$host}", 600, function () use ($host) {
            return Tenant::where('domain', $host)->first();
        });

        if (!$tenant || $host === env('APP_HOST') || $host === '127.0.0.1' || $host === 'localhost') {
            FacadesTenants::switchToSystem();
            return $next($request);
        }

        FacadesTenants::switchToTenant($tenant);

        if ($auth === 'auth' && $user->id != FacadesTenants::getTenantUser()->id) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return $next($request);
    }
}
