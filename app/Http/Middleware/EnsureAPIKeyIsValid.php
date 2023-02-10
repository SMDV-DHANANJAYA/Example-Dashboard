<?php

namespace App\Http\Middleware;

use Closure;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnsureAPIKeyIsValid
{
    use ApiResponseHelpers;

    /**
     * Handle an incoming request.
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('x-api-key') != config('config.api-key')) {
            return $this->respondUnAuthenticated("Invalid API Key");
        }
        return $next($request);
    }
}
