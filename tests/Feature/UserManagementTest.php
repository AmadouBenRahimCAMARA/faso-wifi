<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_users_list()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.users'));

        $response->assertStatus(200);
        $response->assertSee($user->nom);
        $response->assertSee($user->email);
    }

    public function test_admin_can_view_user_details()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.users.show', $user->id));

        $response->assertStatus(200);
        $response->assertSee($user->nom);
        $response->assertSee($user->email);
    }

    public function test_admin_can_edit_user()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.users.edit', $user->id));
        $response->assertStatus(200);

        $updatedData = [
            'nom' => 'Updated Name',
            'prenom' => 'Updated Prenom',
            'email' => 'updated@example.com',
            'phone' => '12345678',
            'role' => 'user',
        ];

        $response = $this->actingAs($admin)->put(route('admin.users.update', $user->id), $updatedData);

        $response->assertRedirect(route('admin.users'));
        $this->assertDatabaseHas('users', ['email' => 'updated@example.com']);
    }

    public function test_admin_can_toggle_user_status()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['status' => 'active']);

        $response = $this->actingAs($admin)->post(route('admin.users.toggleStatus', $user->id));

        $response->assertRedirect();
        $this->assertEquals('banned', $user->fresh()->status);

        $response = $this->actingAs($admin)->post(route('admin.users.toggleStatus', $user->id));
        
        $this->assertEquals('active', $user->fresh()->status);
    }

    public function test_non_admin_cannot_access_user_management()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get(route('admin.users'));
        $response->assertStatus(302); // Redirects to home
    }
}
