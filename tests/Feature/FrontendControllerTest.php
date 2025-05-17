<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Setting;

class FrontendControllerTest extends ControllerTestCase
{
    public function test_guest_can_view_home_page()
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertViewIs('frontend.home');
    }

    public function test_home_page_shows_featured_services()
    {
        $services = Service::factory()->count(6)->create();

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertViewHas('featuredServices', function ($viewServices) use ($services) {
            return $viewServices->count() === $services->count();
        });
    }

    public function test_home_page_shows_featured_employees()
    {
        $employees = Employee::factory()->count(4)->create();

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertViewHas('featuredEmployees', function ($viewEmployees) use ($employees) {
            return $viewEmployees->count() === $employees->count();
        });
    }

    public function test_guest_can_view_services_page()
    {
        $response = $this->get(route('services'));
        $response->assertStatus(200);
        $response->assertViewIs('frontend.services');
    }

    public function test_services_page_shows_categories()
    {
        $categories = Category::factory()->count(5)->create();

        $response = $this->get(route('services'));
        $response->assertStatus(200);
        $response->assertViewHas('categories', function ($viewCategories) use ($categories) {
            return $viewCategories->count() === $categories->count();
        });
    }

    public function test_guest_can_view_about_page()
    {
        $response = $this->get(route('about'));
        $response->assertStatus(200);
        $response->assertViewIs('frontend.about');
    }

    public function test_about_page_shows_business_info()
    {
        $settings = [
            'business_name' => 'Test Salon',
            'business_description' => 'Test Description',
            'business_address' => '123 Test Street',
            'business_phone' => '123-456-7890',
            'business_email' => 'test@example.com'
        ];

        foreach ($settings as $key => $value) {
            Setting::factory()->create([
                'key' => $key,
                'value' => $value
            ]);
        }

        $response = $this->get(route('about'));
        $response->assertStatus(200);
        $response->assertViewHas('settings', function ($viewSettings) use ($settings) {
            foreach ($settings as $key => $value) {
                if (!isset($viewSettings[$key]) || $viewSettings[$key] !== $value) {
                    return false;
                }
            }
            return true;
        });
    }

    public function test_guest_can_view_contact_page()
    {
        $response = $this->get(route('contact'));
        $response->assertStatus(200);
        $response->assertViewIs('frontend.contact');
    }

    public function test_contact_page_shows_contact_info()
    {
        $settings = [
            'business_address' => '123 Test Street',
            'business_phone' => '123-456-7890',
            'business_email' => 'test@example.com',
            'working_hours' => '9:00 AM - 6:00 PM'
        ];

        foreach ($settings as $key => $value) {
            Setting::factory()->create([
                'key' => $key,
                'value' => $value
            ]);
        }

        $response = $this->get(route('contact'));
        $response->assertStatus(200);
        $response->assertViewHas('settings', function ($viewSettings) use ($settings) {
            foreach ($settings as $key => $value) {
                if (!isset($viewSettings[$key]) || $viewSettings[$key] !== $value) {
                    return false;
                }
            }
            return true;
        });
    }
} 