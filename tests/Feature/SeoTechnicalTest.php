<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTechnicalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test dynamic sitemap.xml returns 200 and valid XML.
     */
    public function test_sitemap_xml_returns_valid_xml()
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $this->assertStringContainsString('xml', $response->headers->get('content-type'));
        $this->assertStringContainsString('<urlset', $response->getContent());
        $this->assertStringContainsString('<loc>', $response->getContent());
    }

    /**
     * Test robots.txt contains required disallow rules and sitemap declaration.
     */
    public function test_robots_txt_contains_required_rules()
    {
        $robotsPath = public_path('robots.txt');
        $this->assertFileExists($robotsPath);

        $content = file_get_contents($robotsPath);
        $this->assertStringContainsString('Disallow: /admintv', $content);
        $this->assertStringContainsString('Disallow: /admin', $content);
        $this->assertStringContainsString('Disallow: /cart', $content);
        $this->assertStringContainsString('Disallow: /checkout', $content);
        $this->assertStringContainsString('Sitemap:', $content);
    }

    /**
     * Test homepage contains valid og:type website, canonical link, and viewport.
     */
    public function test_homepage_contains_canonical_and_og_type_website()
    {
        \App\Models\System::create([
            'name_vn' => 'Base Interior',
            'name_en' => 'Base Interior',
            'email' => 'contact@base.local',
            'phone' => '0979248298',
            'address_vn' => 'Ho Chi Minh City',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $content = $response->getContent();

        $this->assertStringContainsString('<meta property="og:type" content="website">', $content);
        $this->assertStringContainsString('name="viewport"', $content);
        $this->assertStringContainsString('rel="canonical"', $content);

        while (ob_get_level() > 1) {
            ob_end_clean();
        }
    }
}
