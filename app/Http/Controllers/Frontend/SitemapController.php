<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\SitemapService;

class SitemapController extends Controller
{
    public function __construct(
        protected SitemapService $sitemapService
    ) {}

    /**
     * Render dynamic XML sitemap with 12h-24h caching.
     */
    public function index()
    {
        $xmlContent = $this->sitemapService->generateXml();

        return response($xmlContent, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }
}
