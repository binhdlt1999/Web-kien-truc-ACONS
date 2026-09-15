<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_non_admin_user_cannot_access_dashboard(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_login_and_open_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@acons.local',
            'password' => 'secret-password',
            'is_admin' => true,
        ]);

        $this->post(route('admin.login.store'), [
            'email' => $admin->email,
            'password' => 'secret-password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get(route('admin.dashboard'))->assertOk()->assertSee('Tổng quan hệ thống');
    }
}
