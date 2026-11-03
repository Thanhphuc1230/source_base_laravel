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
}
