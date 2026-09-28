<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class Visit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Bỏ qua các request không hợp lệ: Chỉ xử lý GET thông thường, bỏ qua AJAX, API, expectsJson và route admin
        if (! $request->isMethod('GET')
            || $request->ajax()
            || $request->expectsJson()
            || $request->is('api/*')
            || $request->is('admin/*')
        ) {
            return $next($request);
        }

        // 2. Loại trừ Quản trị viên / Người dùng nội bộ đã đăng nhập
        if (Auth::check()) {
            return $next($request);
        }

        // 3. Lọc sạch Bot, Crawler, Headless Browser, SEO Scrapers
        if ($this->isCrawler($request->userAgent())) {
            return $next($request);
        }

        // 4. Cơ chế Daily Unique Visitors (Lượt khách duy nhất trong ngày)
        $today = now()->toDateString();
        $secondsUntilEndOfDay = max(60, (int) now()->diffInSeconds(now()->endOfDay()));

        $visitorId = $request->cookie('site_visit_id');
        $hasCookie = ! empty($visitorId) && is_string($visitorId);

        $fingerprint = hash('sha256', ($request->ip() ?? '') . '|' . ($request->userAgent() ?? ''));
        $fingerprintKey = 'visit_fp_' . $today . '_' . $fingerprint;

        // Trường hợp A: Khách đã có Cookie site_visit_id từ trước
        if ($hasCookie) {
            $cookieKey = 'visit_tracked_' . $today . '_' . hash('sha256', $visitorId);

            // Nếu đã được đếm hôm nay qua Cookie -> Bỏ qua
            if (Cache::has($cookieKey)) {
                return $next($request);
            }

            // Nếu đã được đếm hôm nay qua IP + UA (fingerprint dự phòng) -> Lưu cookie key và bỏ qua
            if (Cache::has($fingerprintKey)) {
                Cache::put($cookieKey, true, $secondsUntilEndOfDay);
                return $next($request);
            }

            // Thử xác nhận lượt truy cập bằng atomic add
            $addedCookie = Cache::add($cookieKey, true, $secondsUntilEndOfDay);
            $addedFp = Cache::add($fingerprintKey, true, $secondsUntilEndOfDay);

            if ($addedCookie && $addedFp) {
                $this->recordVisit($today);
            }

            return $next($request);
        }

        // Trường hợp B: Khách mới hoặc khách chặn Cookie / Duyệt ẩn danh
        $visitorId = (string) Str::uuid();
        $cookieKey = 'visit_tracked_' . $today . '_' . hash('sha256', $visitorId);

        // Luôn queue Cookie site_visit_id (lưu 30 ngày) cho các request tiếp theo
        Cookie::queue('site_visit_id', $visitorId, 60 * 24 * 30);

        // Kiểm tra fingerprint dự phòng xem hôm nay IP + UserAgent này đã được đếm chưa
        if (Cache::has($fingerprintKey)) {
            Cache::put($cookieKey, true, $secondsUntilEndOfDay);
            return $next($request);
        }

        // Cố gắng đặt khóa fingerprint nguyên tử (atomic)
        if (Cache::add($fingerprintKey, true, $secondsUntilEndOfDay)) {
            Cache::put($cookieKey, true, $secondsUntilEndOfDay);
            $this->recordVisit($today);
        } else {
            Cache::put($cookieKey, true, $secondsUntilEndOfDay);
        }

        return $next($request);
    }

    /**
     * Ghi nhận lượt truy cập trực tiếp vào DB an toàn, không qua Eloquent Events / Cachable flush.
     */
    protected function recordVisit(string $today): void
    {
        $updated = DB::table('tp_analytics')
            ->where('visit_date', $today)
            ->increment('visit_count');

        if (! $updated) {
            try {
                DB::table('tp_analytics')->insert([
                    'visit_date' => $today,
                    'visit_count' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Throwable $e) {
                // Xử lý race condition khi nhiều request đầu ngày insert đồng thời
                DB::table('tp_analytics')
                    ->where('visit_date', $today)
                    ->increment('visit_count');
            }
        }
    }

    /**
     * Nhận diện Bot, Crawler, Scraper, SEO tools, headless browser & scripts.
     */
    public function isCrawler(?string $userAgent): bool
    {
        if (empty($userAgent) || strlen(trim($userAgent)) < 10) {
            return true;
        }

        $ua = strtolower($userAgent);

        $crawlerPatterns = [
            // Search Engine Bots
            'googlebot',
            'bingbot',
            'slurp',
            'duckduckbot',
            'baiduspider',
            'yandexbot',
            'sogou',
            'exabot',
            'ia_archiver',

            // SEO Tools & Crawlers
            'ahrefsbot',
            'semrushbot',
            'dotbot',
            'mj12bot',
            'petalbot',
            'megaindex',
            'serpstatbot',
            'zoominfobot',
            'screaming frog',
            'sitebulb',
            'rogerbot',
            'seokicks',
            'blexbot',
            'searchmetrics',
            'woorank',

            // Social Media Crawlers & Link Previews
            'facebookexternalhit',
            'twitterbot',
            'linkedinbot',
            'slackbot',
            'telegrambot',
            'whatsapp',
            'skypeuripreview',
            'discordbot',
            'pinterest',

            // Headless Browsers, Automation, HTTP Clients & Security Scanners
            'bytespider',
            'curl',
            'wget',
            'python',
            'guzzlehttp',
            'postman',
            'headlesschrome',
            'phantomjs',
            'selenium',
            'puppeteer',
            'playwright',
            'uptime',
            'lighthouse',
            'apachebench',
            'httpclient',
            'java',
            'go-http-client',
            'node-fetch',
            'axios',
            'urllib',
            'censys',
            'shodan',
            'nmap',
            'nikto',
            'masscan',
            'zgrab',
            'crawler',
            'spider',
            'bot/',
            'bot;',
            'bot-',
            'bot_',
        ];

        foreach ($crawlerPatterns as $pattern) {
            if (str_contains($ua, $pattern)) {
                return true;
            }
        }

        // Bắt các pattern bot độc lập
        if (preg_match('/(\b|_)(bot|crawl|crawler|spider)\b/i', $ua)) {
            return true;
        }

        return false;
    }
}