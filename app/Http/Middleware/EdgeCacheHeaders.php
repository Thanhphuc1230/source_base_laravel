<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EdgeCacheHeaders
{
    /**
     * Attach edge-safe Cache-Control headers for guest GET/HEAD frontend pages.
     */
    public function handle(Request $request, Closure $next, int $sMaxAge = 3600): Response
    {
        $response = $next($request);

        if (
            ! in_array($request->method(), ['GET', 'HEAD'], true)
            || Auth::check()
            || $request->is('admin*', 'cart*', 'checkout*', 'api*', 'login*', 'register*')
            || $response->getStatusCode() !== 200
            || $request->session()->has('errors')
        ) {
            $response->headers->set('Cache-Control', 'private, no-store');

            return $response;
        }

        $response->headers->set(
            'Cache-Control',
            "public, max-age=60, s-maxage={$sMaxAge}, stale-while-revalidate=60"
        );
        $response->headers->set('Vary', 'Accept-Encoding', false);

        return $response;
    }
}
