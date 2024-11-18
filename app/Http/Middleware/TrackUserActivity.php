<?php

namespace App\Http\Middleware;

use App\Events\UserOnlineEvent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) { 
            event(new UserOnlineEvent(Auth::user()));
             
        }

        return $next($request);
    }
}
