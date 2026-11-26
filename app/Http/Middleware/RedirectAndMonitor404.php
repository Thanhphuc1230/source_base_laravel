<?php

namespace App\Http\Middleware;

use App\Services\RedirectService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectAndMonitor404
{
    protected RedirectService $redirectService;

    public function __construct(RedirectService $redirectService)
    {
        $this->redirectService = $redirectService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (
            ! in_array($request->method(), ['GET', 'HEAD'], true)
            || $request->is('admin*', 'api*')
        ) {
            return $next($request);
        }

        $path = '/' . ltrim($request->path(), '/');
        $redirect = $this->redirectService->resolveRedirect($path);

        if ($redirect && ! empty($redirect['target_url'])) {
            $this->redirectService->recordHit((int) $redirect['id']);

            return redirect()->to($redirect['target_url'], (int) $redirect['status_code']);
        }

        $response = $next($request);

        if ($response->getStatusCode() === 404 && ! $request->expectsJson()) {
            $this->redirectService->record404($path);
        }

        return $response;
    }
}
