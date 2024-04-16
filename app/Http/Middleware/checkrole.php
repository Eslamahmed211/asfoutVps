<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkrole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next , $role)
    {

        if (!in_array($role ,(json_decode(auth()->user()->permissions)) )) {
            abort(403, 'لا يوجد لديك صلاحية ');
        }
     
        return $next($request);
       
    }
}
