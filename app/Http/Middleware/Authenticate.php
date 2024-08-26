<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
     protected function authenticate($request, array $guards)
     {
        if($this->auth->guard($guards)->guest()){
            $this->unauthenticated($request,$guards);
        }

        if(Auth::check() && Auth::user()->is_block){
            Auth::logout();
            return response()->json([
                'message' => 'your account is blocked'
            ],403);
        }
     }

    protected function redirectTo(Request $request): ?string
    {
        if(Auth::check() && Auth::user()->is_block){
            Auth::logout();
            return response()->json([
                'message' => 'your account is blocked'
            ],403);
        }

        return $request->expectsJson() ? null : route('login');
    }
}



// namespace App\Http\Middleware;

// use Illuminate\Auth\Middleware\Authenticate as Middleware;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

// class Authenticate extends Middleware
// {
//     protected function authenticate($request, array $guards)
//     {
//         if ($this->auth->guard($guards)->guest()) {
//             $this->unauthenticated($request, $guards);
//         }

//         if (Auth::check() && Auth::user()->is_block) {
//             Auth::logout();
//             return $this->blockedResponse($request);
//         }
//     }

//     protected function redirectTo(Request $request): ?string
//     {
//         if (!$request->expectsJson()) {
//             return route('login');
//         }

//         return null;
//     }

//     protected function unauthenticated($request, array $guards)
//     {
//         if ($request->expectsJson()) {
//             abort(response()->json(['message' => 'Unauthenticated.'], 401));
//         }

//         redirect()->guest(route('login'));
//     }

//     protected function blockedResponse($request)
//     {
//         $message = ['message' => 'your account is blocked'];
//         if ($request->expectsJson()) {
//             return response()->json($message, 403);
//         }

//         return redirect()->guest(route('login'))->withErrors($message);
//     }
// }
