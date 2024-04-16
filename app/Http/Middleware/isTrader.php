<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isTrader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!settings('opening')) {
            abort(403, "الموقع الان تحت الصيانة");
        }

      if (auth()->user()->role == "trader") {
        return $next($request);
      }else{
        abort("403" , 'انت لست تاجر');
      }
    }
}
