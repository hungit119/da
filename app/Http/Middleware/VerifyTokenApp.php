<?php

namespace App\Http\Middleware;

// use App\Models\Globals\Users;
use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class VerifyTokenApp
{
    public function __construct(
    ) {
    }

    public function handle($request, Closure $next, $guard = null)
    {
        $user = $this->verifyToken($request);

        if (!$user) {
            return response()->json([
                "status" => "fail",
                "message" => 'Permission denied',
                "data" => null
            ],401);
        }

        $request->attributes->add(['user' => $user]);
        $request->auth = $user;

        return $next($request);
    }

    public function verifyToken(Request $request)
    {
        $token = $request->header('token');

        if (!$token) {
            $token = $request->input('token');
        }
        if (!$token || $token == 'null') {
            return false;
        }
        if ($token == env('TOKEN_TO_SERVER')) {
            return true;
        }

        try {
            $decode_token = JWT::decode($token, new Key(env('KEY_TOKEN'), 'HS256'));
        } catch (ExpiredException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
        $user = (array)$decode_token;
        return $user;
    }
}
