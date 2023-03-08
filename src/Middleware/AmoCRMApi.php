<?php

namespace Biohazard\AmoCRMApi\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use League\OAuth2\Client\Token\AccessToken;
use Illuminate\Support\Facades\Route;

class AmoCRMApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $accessToken = app(AccessToken::class);
        $middlewareRedirect = config('amocrm-api.middleware_redirect');

        if ($accessToken->hasExpired() && Route::has($middlewareRedirect)) {
            return redirect()->route($middlewareRedirect);
        }

        return $next($request);
    }
}
