<?php

namespace Tests\Feature;

use App\Models\User;

class UserControllerTest extends ControllerTestCase
{
    public function test_admin_can_view_users()
    {
        $this->actingAsAdmin();

        $response = $this->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
    }

    public function test_admin_can_create_user()
    {
        $this->actingAsAdmin();

        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user'
        ];

        $response = $this->post(route('admin.users.store'), $userData);
        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
            'role' => $userData['role']
        ]);
    }

    public function test_admin_can_update_user()
    {
        $this->actingAsAdmin();
        
        $user = User::factory()->create();

        $updateData = [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
            'role' => 'admin'
        ];

        $response = $this->put(route('admin.users.update', $user), $updateData);
        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', $updateData);
    }

    public function test_admin_can_delete_user()
    {
        $this->actingAsAdmin();
        
        $user = User::factory()->create();

        $response = $this->delete(route('admin.users.destroy', $user));
        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_user_requires_name_and_email()
    {
        $this->actingAsAdmin();

        $userData = [
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user'
        ];

        $response = $this->post(route('admin.users.store'), $userData);
        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function test_user_email_must_be_unique()
    {
        $this->actingAsAdmin();
        
        User::factory()->create(['email' => 'existing@example.com']);

        $userData = [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user'
        ];

        $response = $this->post(route('admin.users.store'), $userData);
        $response->assertSessionHasErrors('email');
    }

    public function test_user_password_must_be_confirmed()
    {
        $this->actingAsAdmin();

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different123',
            'role' => 'user'
        ];

        $response = $this->post(route('admin.users.store'), $userData);
        $response->assertSessionHasErrors('password');
    }

    public function test_admin_can_change_user_role()
    {
        $this->actingAsAdmin();
        
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->put(route('admin.users.update', $user), [
            'role' => 'admin'
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'admin'
        ]);
    }
} 