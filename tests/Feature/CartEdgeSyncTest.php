<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartEdgeSyncTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test that /cart/count endpoint returns valid JSON with total items.
     */
    public function test_cart_count_endpoint_returns_json(): void
    {
        $response = $this->get(route('web.cartCount'));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'totalItems' => 0,
        ]);
    }

    /**
     * Test that master blade contains speculation rules script for edge prerendering.
     */
    public function test_speculation_rules_rendered_in_frontend(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('speculationrules', false);
        $response->assertSee('"prerender"', false);
        $response->assertSee('moderate', false);
    }

    /**
     * Test that theme-style.css contains @view-transition rule.
     */
    public function test_view_transition_css_exists(): void
    {
        $cssPath = public_path('frontend/css/theme-style.css');
        $this->assertFileExists($cssPath);

        $cssContent = file_get_contents($cssPath);
        $this->assertStringContainsString('@view-transition', $cssContent);
        $this->assertStringContainsString('view-transition-name: site-header', $cssContent);
    }
}
