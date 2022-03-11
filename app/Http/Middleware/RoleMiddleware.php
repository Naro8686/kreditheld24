<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{

    /**
     * @param Request $request
     * @param Closure $next
     * @param $role
     * @param $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role, $permission = null)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        if (!auth()->user()->hasRole($role) && auth()->user()->isAdmin()) {
            return redirect('/admin');
        }
        if (!auth()->user()->hasRole($role)) {
            abort(404);
        }

        if ($permission !== null && !auth()->user()->can($permission)) {
            abort(403);
        }
        return $next($request);
    }
}
