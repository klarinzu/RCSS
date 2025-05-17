<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileControllerTest extends ControllerTestCase
{
    public function test_user_can_view_their_profile()
    {
        $this->actingAsUser();

        $response = $this->get(route('profile.show'));
        $response->assertStatus(200);
        $response->assertViewIs('profile.show');
    }

    public function test_user_can_update_their_profile()
    {
        $this->actingAsUser();

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '123-456-7890',
            'address' => '123 New Street'
        ];

        $response = $this->put(route('profile.update'), $updateData);
        $response->assertRedirect(route('profile.show'));
        $this->assertDatabaseHas('users', $updateData);
    }

    public function test_user_can_update_their_password()
    {
        $this->actingAsUser();

        $passwordData = [
            'current_password' => 'password',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ];

        $response = $this->put(route('profile.password'), $passwordData);
        $response->assertRedirect(route('profile.show'));
        $this->assertTrue(password_verify('newpassword123', $this->user->fresh()->password));
    }

    public function test_user_can_upload_profile_picture()
    {
        $this->actingAsUser();
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->post(route('profile.avatar'), [
            'avatar' => $file
        ]);

        $response->assertRedirect(route('profile.show'));
        Storage::disk('public')->assertExists('avatars/' . $file->hashName());
    }

    public function test_user_cannot_update_email_to_existing_one()
    {
        $this->actingAsUser();
        $otherUser = User::factory()->create(['email' => 'existing@example.com']);

        $updateData = [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'phone' => '123-456-7890',
            'address' => '123 Test Street'
        ];

        $response = $this->put(route('profile.update'), $updateData);
        $response->assertSessionHasErrors('email');
    }

    public function test_user_cannot_update_password_without_current_password()
    {
        $this->actingAsUser();

        $passwordData = [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ];

        $response = $this->put(route('profile.password'), $passwordData);
        $response->assertSessionHasErrors('current_password');
    }
} 