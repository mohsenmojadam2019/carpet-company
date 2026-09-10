<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminInfrastructureTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_authenticated_user_without_permission_is_forbidden(): void
    {
        $user=User::create(['name'=>'No Access','email'=>'no-access@example.test','password'=>'password123']);
        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_super_admin_can_open_admin_dashboard(): void
    {
        $this->seed();
        $user=User::create(['name'=>'Admin','email'=>'admin-test@example.test','password'=>'password123']);
        $user->assignRole(Role::findByName('super-admin'));
        $this->actingAs($user)->get('/admin')->assertOk();
    }

    public function test_media_and_permission_tables_exist(): void
    {
        $this->assertTrue(Schema::hasTable('media'));
        $this->assertTrue(Schema::hasTable('permissions'));
        $this->assertTrue(Schema::hasTable('model_has_roles'));
        $this->assertTrue(Schema::hasTable('settings'));
        $this->assertTrue(Schema::hasTable('menu_items'));
    }
}
