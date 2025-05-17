<?php

namespace Tests\Feature;

use App\Models\Setting;

class SettingControllerTest extends ControllerTestCase
{
    public function test_admin_can_view_settings()
    {
        $this->actingAsAdmin();

        $response = $this->get(route('admin.settings.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.settings.index');
    }

    public function test_admin_can_update_settings()
    {
        $this->actingAsAdmin();

        $settings = [
            'business_name' => 'Updated Salon Name',
            'business_address' => '123 New Street',
            'business_phone' => '123-456-7890',
            'business_email' => 'new@example.com',
            'working_hours' => '9:00 AM - 6:00 PM'
        ];

        $response = $this->put(route('admin.settings.update'), $settings);
        $response->assertRedirect(route('admin.settings.index'));

        foreach ($settings as $key => $value) {
            $this->assertDatabaseHas('settings', [
                'key' => $key,
                'value' => $value
            ]);
        }
    }

    public function test_settings_are_required()
    {
        $this->actingAsAdmin();

        $response = $this->put(route('admin.settings.update'), []);
        $response->assertSessionHasErrors(['business_name', 'business_address', 'business_phone', 'business_email']);
    }

    public function test_business_email_must_be_valid()
    {
        $this->actingAsAdmin();

        $settings = [
            'business_name' => 'Test Salon',
            'business_address' => '123 Test Street',
            'business_phone' => '123-456-7890',
            'business_email' => 'invalid-email',
            'working_hours' => '9:00 AM - 6:00 PM'
        ];

        $response = $this->put(route('admin.settings.update'), $settings);
        $response->assertSessionHasErrors('business_email');
    }

    public function test_business_phone_must_be_valid()
    {
        $this->actingAsAdmin();

        $settings = [
            'business_name' => 'Test Salon',
            'business_address' => '123 Test Street',
            'business_phone' => 'invalid-phone',
            'business_email' => 'test@example.com',
            'working_hours' => '9:00 AM - 6:00 PM'
        ];

        $response = $this->put(route('admin.settings.update'), $settings);
        $response->assertSessionHasErrors('business_phone');
    }
} 