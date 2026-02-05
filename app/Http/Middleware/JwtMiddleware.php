<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
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
        try {
            // Authenticate user via token
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found'
                ], 404);
            }

        } catch (TokenInvalidException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Token is invalid'
            ], 401);
        } catch (TokenExpiredException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Token has expired'
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Authorization token not found'
            ], 400);
        }

        return $next($request);
    }
}
