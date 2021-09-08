<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Session;

class FrontLogin
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
        // echo $currentPath = Route::getFacadeRoot()->current()->uri(); die;
        if(empty(Session::has('frontSession'))){
            // echo $current_url = $_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI']; die
            $current_url = $_SERVER['REQUEST_URI'];
            // $current_url = Route::getFacadeRoot()->current()->uri();
            Session::put('current_url', $current_url);
            return redirect('/');
        }
        // if(empty(Session::has('frontSession'))){
        //     return redirect('/');
        // }
        
        return $next($request);
    }
}
