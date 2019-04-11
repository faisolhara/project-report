<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CustomRedirectIfAuthenticated
{
    public function handle($request, Closure $next)
    {
        if(!empty(\Session::get('user'))){
            return redirect('dashboard');
        }

        return $next($request);
    }
}
