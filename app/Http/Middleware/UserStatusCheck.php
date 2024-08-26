<?php

namespace App\Http\Middleware;

use App\Notifications\AccountInactiveNotification;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class UserStatusCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && !$user->status) {
            auth()->logout();

            $cacheKey = 'user-inactive-notified-' . $user->id;

            if (!Cache::has($cacheKey)) {
                $user->notify(new AccountInactiveNotification());

                Cache::put($cacheKey, true, now()->addHours(12));
            }

            return Redirect::route('login')
                ->withInput(['email' => $user->email])
                ->withErrors([
                'email' => 'Your account is inactive. Please contact your system admin for re-activation.',
            ]);
        }

        return $next($request);
    }
}
