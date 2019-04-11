<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CustomRedirectIfNotAuthenticated
{
    public function handle($request, Closure $next)
    {
        if(empty(\Session::get('user'))){
            return redirect('login');
        }

        return $next($request);
    }
}
