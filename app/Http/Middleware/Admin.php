<?php

namespace App\Http\Middleware;

use App\Permissions;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() or !Auth::user()->group->hasAccess('admin_access'))
            return redirect("/");

        return $next($request);
    }
}