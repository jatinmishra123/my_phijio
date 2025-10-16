<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public static function handle(Request $request, Closure $next)
    {
        if(!session()->has('session_admin')){
           return redirect('/admins');
        }
        $role = getlogindetail('role'); // hopefully 'admin' | 'client'

        // setting the default for this parameter for the current user's role
        \URL::defaults([
            'roleBased' => $role
        ]);

        return $next($request);
    }
}
