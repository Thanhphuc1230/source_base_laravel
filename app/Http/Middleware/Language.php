<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $supported = config('languages.supported', []);
        $aliases = ['vn' => 'vi', 'jp' => 'ja', 'kr' => 'ko', 'cn' => 'zh'];

        // 1. Detect if URL prefix contains a valid locale (e.g. /ja/slug or /en/slug)
        $firstSegment = $request->segment(1);
        $normSegment = $aliases[$firstSegment] ?? $firstSegment;

        if ($firstSegment && array_key_exists($normSegment, $supported)) {
            session()->put('locale', $normSegment);
            App::setLocale($normSegment);
        } else {
            // 2. Fallback to session locale or default
            $locale = session()->get('locale', config('languages.default', 'vi'));
            $norm = $aliases[$locale] ?? $locale;

            if (!array_key_exists($norm, $supported)) {
                $norm = config('languages.default', 'vi');
            }

            session()->put('locale', $norm);
            App::setLocale($norm);
        }

        return $next($request);
    }
}
