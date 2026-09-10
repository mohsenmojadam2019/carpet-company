<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCmsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->admin = User::create(['name'=>'Test Admin','email'=>'admin-test@example.com','password'=>'testing-password']);
        $this->admin->assignRole('super-admin');
    }

    public function test_super_admin_can_render_all_management_sections(): void
    {
        foreach ([
            'admin.dashboard','admin.products.index','admin.categories.index','admin.orders.index','admin.customers.index',
            'admin.projects.index','admin.coupons.index','admin.discount-rules.index','admin.media.index','admin.menus.index',
            'admin.settings.edit','admin.users.index','admin.roles.index','admin.reports.index',
        ] as $route) {
            $this->actingAs($this->admin)->get(route($route))->assertOk();
        }
    }

    public function test_unprivileged_user_cannot_enter_admin(): void
    {
        $user=User::create(['name'=>'Customer','email'=>'customer@example.com','password'=>'testing-password']);
        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_media_library_attaches_product_gallery_image(): void
    {
        Storage::fake('public');
        $product=Product::query()->firstOrFail();
        $media=$product->addMedia(UploadedFile::fake()->image('rug.jpg',800,1000))->toMediaCollection('gallery','public');
        $this->assertDatabaseHas('media',['id'=>$media->id,'model_type'=>Product::class,'model_id'=>$product->id,'collection_name'=>'gallery']);
        $this->assertNotEmpty($product->fresh()->primary_image);
    }

    public function test_static_byekan_font_is_materialized(): void
    {
        $path=public_path('fonts/BYekan.woff2');
        $this->assertFileExists($path);
        $this->assertSame('wOF2',file_get_contents($path,false,null,0,4));
    }

    public function test_private_settings_round_trip_encrypted(): void
    {
        Setting::put('test_secret','top-secret','testing','encrypted',false);
        $row=Setting::where('key','test_secret')->firstOrFail();
        $this->assertNotSame('top-secret',$row->value);
        $this->assertSame('top-secret',Setting::valueOf('test_secret'));
        $this->assertFalse($row->is_public);
    }

    public function test_public_projects_wishlist_compare_and_sitemap_render(): void
    {
        $product=Product::firstOrFail();
        $this->get(route('projects.index'))->assertOk();
        $this->get(route('wishlist.index'))->assertOk();
        $this->get(route('compare.index'))->assertOk();
        $this->post(route('wishlist.toggle',$product))->assertRedirect();
        $this->post(route('compare.toggle',$product))->assertRedirect();
        $this->get(route('sitemap'))->assertOk()->assertHeader('Content-Type','application/xml');
    }
}
