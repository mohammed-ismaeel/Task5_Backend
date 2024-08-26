<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) :Response
    {
        if(Auth::check() && Auth::user()->is_admin){
            return $next($request);
        }
        Auth::logout();
        return redirect()->route('login')->withErrors([
                'message' => 'Access denided. you do not have permission to access this page'
            ]);
        }
    }



    // if (Auth::user()->is_admin == 0) {
    //     return redirect()->route('login')->withErrors([
    //         'message' => 'Access denided. you do not have permission to access this page'
    //     ]);
    // }
    // else if (Auth::user()->is_block == 1) {
    //     return redirect()->route('login')->withErrors([
    //         'message' => 'Access denided. your account is blocked'
    //     ]);
    // }
