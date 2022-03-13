<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * In controller, pass in the form:
     * $this->middleware('role:admin,user,other,roles');
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request):  (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //Grab list of roles as a lower case array
        $roles = array_map('strtolower', array_slice(func_get_args(), 2));

        //If no role passed, let anyone through
        if (count($roles)==0)
          return $next($request);

        //Get user
        $user = Auth::user();

        //Get user role name in lower case
        $user_role = strtolower($user->role);

        //If user doesn't have role, redirect to 403
        if (!in_array($user->role->name, $roles)) {
          abort(403);
        }

        return $next($request);
    }
}
