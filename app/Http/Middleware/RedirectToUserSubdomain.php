<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectToUserSubdomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $scheme = $request->getScheme();
        $user = Auth::user();
        if ($user && $user instanceof User && !$user->isAdmin()) {
            $domain = $user->tenant->domain;

            if ($host !== $domain) {
                $url = $scheme . '://' . $domain . $request->getRequestUri();
                return redirect()->to($url);
            } else {
                return $next($request);
            }
        }

        if ($host !== env('APP_HOST')) {
            return redirect()->to(env('APP_URL'));
        }

        return $next($request);
    }
}
