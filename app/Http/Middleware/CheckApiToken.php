<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Exception;

class CheckApiToken
{

    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        $response = response()->json(['status' => 401, 'msg' => 'Invalid authentication token in request'], 401);
        if ($request->header('apiToken')) {
            try {
                $split = explode("sa3d01", $request->header('apiToken'));
                $user = User::whereJsonContains('apiToken', $split['1'])->first();
            } catch (Exception $e) {
                return $response;
            }
            if (!$user) {
                return $response;
            } else {
                return $next($request);
            }
        } else {
            return $response;
        }
    }
}
