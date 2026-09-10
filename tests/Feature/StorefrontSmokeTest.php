<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_is_rendered(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('خانه فرش')
            ->assertSee('data-rug-peel-v2', false)
            ->assertSee('assets/css/rug-peel.css', false)
            ->assertSee('assets/js/rug-peel.js', false);
    }

    public function test_catalog_page_is_rendered(): void
    {
        $this->get('/shop')->assertOk()->assertSee('فروشگاه');
    }

    public function test_admin_login_page_is_rendered(): void
    {
        $this->get('/admin/login')->assertOk()->assertSee('ورود مدیریت');
    }

    public function test_byekan_font_is_materialized_as_valid_woff2(): void
    {
        $path = public_path('fonts/BYekan.woff2');

        $this->assertFileExists($path);
        $content = file_get_contents($path);
        $this->assertNotFalse($content);
        $this->assertStringStartsWith('wOF2', $content);
        $this->assertSame(20572, strlen($content));
    }
}
