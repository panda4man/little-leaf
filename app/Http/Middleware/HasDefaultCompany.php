<?php

namespace App\Http\Middleware;

use Closure;

class HasDefaultCompany
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->check() && currentCompanySet()) {
              $c = currentCompany();

              if(!$c) {
                  session()->flash('info', 'You have not set any of your companies as the default one.');
                  return redirect()->route('dashboard');
              }
        }

        return $next($request);
    }
}
