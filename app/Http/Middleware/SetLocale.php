<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!empty(Auth::user())) {
            if (in_array(Auth::user()->preferred_language, ['en', 'nl'])) {
                App::setLocale(Auth::user()->preferred_language);
            }
        }

        return $next($request);
    }
}
