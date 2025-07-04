<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]|null  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                if (in_array($user->role, ['admin', 'superadmin'])) {
                    return redirect('/admin/dashboard');
                } elseif ($user->role === 'staff') {
                    return redirect('/user/clock-in');
                }

                // Default fallback (optional)
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
