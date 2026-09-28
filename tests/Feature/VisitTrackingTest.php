<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class VisitTrackingTest extends TestCase
{
    use RefreshDatabase;

    protected string $realUserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    /**
     * 1. Khách thật vào lần đầu: Tăng +1 và nhận cookie site_visit_id.
     */
    public function test_real_user_first_visit_increments_count(): void
    {
        $today = now()->toDateString();

        $response = $this->withServerVariables([
            'HTTP_USER_AGENT' => $this->realUserAgent,
            'REMOTE_ADDR' => '192.168.1.100',
        ])->get('/');

        // Số lượt truy cập hôm nay phải là 1
        $count = DB::table('tp_analytics')->where('visit_date', $today)->value('visit_count');
        $this->assertEquals(1, $count);

        // Phải được cấp cookie site_visit_id
        $response->assertCookie('site_visit_id');
    }

    /**
     * 2. Khách thật bấm trang tiếp theo hoặc F5: Không tăng (+0).
     */
    public function test_real_user_f5_or_next_page_does_not_increment(): void
    {
        $today = now()->toDateString();

        // Lần 1: Khách vào trang chủ
        $firstResponse = $this->withServerVariables([
            'HTTP_USER_AGENT' => $this->realUserAgent,
            'REMOTE_ADDR' => '192.168.1.100',
        ])->get('/');

        $firstCookie = $firstResponse->getCookie('site_visit_id');
        $this->assertNotNull($firstCookie);
        $visitorId = $firstCookie->getValue();

        $countAfterFirst = DB::table('tp_analytics')->where('visit_date', $today)->value('visit_count');
        $this->assertEquals(1, $countAfterFirst);

        // Lần 2: F5 lại trang với cookie site_visit_id đã nhận
        $this->withCookies(['site_visit_id' => $visitorId])
            ->withServerVariables([
                'HTTP_USER_AGENT' => $this->realUserAgent,
                'REMOTE_ADDR' => '192.168.1.100',
            ])->get('/');

        $countAfterSecond = DB::table('tp_analytics')->where('visit_date', $today)->value('visit_count');
        $this->assertEquals(1, $countAfterSecond, 'F5 không được tăng lượt truy cập');

        // Lần 3: Bấm sang trang giỏ hàng hoặc trang khác
        $this->withCookies(['site_visit_id' => $visitorId])
            ->withServerVariables([
                'HTTP_USER_AGENT' => $this->realUserAgent,
                'REMOTE_ADDR' => '192.168.1.100',
            ])->get('/cart');

        $countAfterThird = DB::table('tp_analytics')->where('visit_date', $today)->value('visit_count');
        $this->assertEquals(1, $countAfterThird, 'Bấm trang tiếp theo không được tăng lượt truy cập');
    }

    /**
     * Khách ẩn danh hoặc chặn cookie bấm nhiều trang liên tục: Fingerprint IP + UA chặn không cho tăng dồn (+0).
     */
    public function test_incognito_user_blocking_cookies_does_not_increment_repeatedly(): void
    {
        $today = now()->toDateString();

        // Lần 1: Khách ẩn danh vào trang
        $this->withServerVariables([
            'HTTP_USER_AGENT' => $this->realUserAgent,
            'REMOTE_ADDR' => '192.168.1.150',
        ])->get('/');

        $countAfterFirst = DB::table('tp_analytics')->where('visit_date', $today)->value('visit_count');
        $this->assertEquals(1, $countAfterFirst);

        // Lần 2: Khách ẩn danh không gửi lại cookie nhưng giữ nguyên IP + UA
        $this->withServerVariables([
            'HTTP_USER_AGENT' => $this->realUserAgent,
            'REMOTE_ADDR' => '192.168.1.150',
        ])->get('/');

        $countAfterSecond = DB::table('tp_analytics')->where('visit_date', $today)->value('visit_count');
        $this->assertEquals(1, $countAfterSecond, 'Khách chặn cookie bấm nhiều trang không bị cộng dồn số ảo');
    }

    /**
     * 3. Bot/crawler quét qua: Không tăng (+0).
     */
    public function test_bots_and_crawlers_do_not_increment(): void
    {
        $today = now()->toDateString();

        $crawlers = [
            'Googlebot/2.1 (+http://www.google.com/bot.html)',
            'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)',
            'Mozilla/5.0 (compatible; AhrefsBot/7.0; +http://ahrefs.com/robot/)',
            'SemrushBot/7~bl (http://www.semrush.com/bot.html)',
            'Mozilla/5.0 (compatible; Bytespider; spider-feedback@bytedance.com)',
            'curl/7.88.1',
            'PostmanRuntime/7.32.3',
            'python-requests/2.31.0',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/114.0.5735.198 Safari/537.36',
            'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)',
            'Twitterbot/1.0',
            'TelegramBot (like TwitterBot)',
            'WhatsApp/2.21.12.21 A',
            'Wget/1.21.3',
        ];

        foreach ($crawlers as $crawlerUa) {
            $this->withServerVariables([
                'HTTP_USER_AGENT' => $crawlerUa,
                'REMOTE_ADDR' => '66.249.66.1',
            ])->get('/');
        }

        // Bảng thống kê không được phát sinh bất kỳ dòng nào
        $count = DB::table('tp_analytics')->where('visit_date', $today)->value('visit_count');
        $this->assertNull($count, 'Bot / Crawler không được ghi nhận lượt truy cập');
    }

    /**
     * 4. Admin đăng nhập vào trang: Không tăng (+0).
     */
    public function test_authenticated_admin_does_not_increment(): void
    {
        $today = now()->toDateString();

        $user = User::factory()->create();

        $this->actingAs($user)
            ->withServerVariables([
                'HTTP_USER_AGENT' => $this->realUserAgent,
                'REMOTE_ADDR' => '192.168.1.100',
            ])->get('/');

        $count = DB::table('tp_analytics')->where('visit_date', $today)->value('visit_count');
        $this->assertNull($count, 'Admin đăng nhập không được ghi nhận lượt truy cập');
    }

    /**
     * 5. AJAX / expectsJson không được tăng (+0).
     */
    public function test_ajax_and_json_requests_do_not_increment(): void
    {
        $today = now()->toDateString();

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->withServerVariables([
                'HTTP_USER_AGENT' => $this->realUserAgent,
                'REMOTE_ADDR' => '192.168.1.100',
            ])->get('/');

        $count = DB::table('tp_analytics')->where('visit_date', $today)->value('visit_count');
        $this->assertNull($count, 'AJAX request không được ghi nhận lượt truy cập');
    }
}
