<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_is_rendered(): void
    {
        $this->get('/')->assertOk()->assertSee('خانه فرش');
    }

    public function test_catalog_page_is_rendered(): void
    {
        $this->get('/shop')->assertOk()->assertSee('فروشگاه');
    }

    public function test_admin_login_page_is_rendered(): void
    {
        $this->get('/admin/login')->assertOk()->assertSee('ورود مدیریت');
    }

    public function test_byekan_font_is_valid_woff2(): void
    {
        $response = $this->get('/fonts/BYekan.woff2');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'font/woff2');
        $this->assertStringStartsWith('wOF2', $response->getContent());
        $this->assertSame(20572, strlen($response->getContent()));
    }
}
