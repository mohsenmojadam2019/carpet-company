<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
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
            ->assertSee('data-room-selector', false)
            ->assertSee('assets/css/room-showcase.css', false)
            ->assertSee('assets/js/room-showcase.js', false);
    }

    public function test_home_room_slider_has_no_per_category_product_limit(): void
    {
        $category = Category::create([
            'name' => 'دسته تست اسلایدر',
            'slug' => 'slider-test-category',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        foreach (range(1, 12) as $index) {
            Product::create([
                'category_id' => $category->id,
                'name' => 'محصول اسلایدر '.$index,
                'slug' => 'slider-product-'.$index,
                'sku' => 'SLIDER-'.str_pad((string) $index, 3, '0', STR_PAD_LEFT),
                'price' => 1000000 + ($index * 1000),
                'stock' => 10,
                'featured' => $index === 1,
                'is_active' => true,
            ]);
        }

        $this->get('/')
            ->assertOk()
            ->assertSee('محصول اسلایدر 1')
            ->assertSee('محصول اسلایدر 9')
            ->assertSee('محصول اسلایدر 12');
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
