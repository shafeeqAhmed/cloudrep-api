<?php

namespace App\Http\Middleware;

use App\Models\VerificationCode;
use Closure;
use Illuminate\Http\Request;

class TwoFa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $isVerifed =  VerificationCode::isVerified($request->user()?->id);
        if ($isVerifed) {
            return response()->json([
                'status' => false,
                'message' => 'Please verified 2fa authentification',
                'data' => ''
            ], 403);
        }

        return $next($request);
    }
}
