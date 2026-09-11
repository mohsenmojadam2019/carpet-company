<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialsAndInquiriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_corporate_pages_and_large_navigation_render(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('سفارش اختصاصی')
            ->assertSee('درباره ما')
            ->assertSee('تماس با ما')
            ->assertSee('redcoweb.ir')
            ->assertSee('CUSTOMER STORIES');

        foreach (['/about','/contact','/custom-order','/services','/faq'] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_contact_form_creates_trackable_inquiry(): void
    {
        $token = 'contact-form-csrf-token';

        $response = $this->withSession(['_token' => $token])->post(route('contact.store'), [
            '_token' => $token,
            'name' => 'کاربر تست',
            'phone' => '09120000099',
            'email' => 'contact@example.test',
            'subject' => 'مشاوره خرید',
            'message' => 'برای انتخاب فرش نشیمن مشاوره می‌خواهم.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('inquiries', [
            'type' => 'contact',
            'phone' => '09120000099',
            'status' => 'new',
        ]);
    }

    public function test_custom_order_form_creates_structured_request(): void
    {
        $token = 'custom-order-csrf-token';

        $response = $this->withSession(['_token' => $token])->post(route('custom-order.store'), [
            '_token' => $token,
            'name' => 'مشتری سفارش اختصاصی',
            'phone' => '09121112222',
            'category' => 'قالی دستباف',
            'width' => 250,
            'height' => 350,
            'budget' => 150000000,
            'message' => 'رنگ زمینه روشن باشد.',
        ]);

        $response->assertRedirect();
        $inquiry = Inquiry::where('phone', '09121112222')->firstOrFail();
        $this->assertSame('custom_order', $inquiry->type);
        $this->assertSame('قالی دستباف', $inquiry->meta['category']);
        $this->assertSame(250.0, (float)$inquiry->meta['width']);
    }

    public function test_active_testimonial_is_rendered_on_homepage(): void
    {
        Testimonial::create([
            'customer_name' => 'مشتری واقعی تست',
            'city' => 'تهران',
            'title' => 'تجربه عالی',
            'body' => 'این متن باید از دیتابیس روی صفحه اصلی دیده شود.',
            'rating' => 5,
            'sort_order' => 1,
            'is_featured' => true,
            'is_active' => true,
            'verified_at' => now(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('مشتری واقعی تست')
            ->assertSee('این متن باید از دیتابیس روی صفحه اصلی دیده شود.');
    }

    public function test_super_admin_can_manage_testimonials_and_inquiries(): void
    {
        $this->seed(DatabaseSeeder::class);
        $admin = User::where('email', 'admin@carpet.local')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.testimonials.index'))
            ->assertOk()
            ->assertSee('نظرات مشتریان');

        $this->actingAs($admin)
            ->get(route('admin.inquiries.index'))
            ->assertOk()
            ->assertSee('درخواست‌های مشتریان');
    }
}
