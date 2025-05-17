<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\Category;

class ServiceControllerTest extends ControllerTestCase
{
    public function test_user_can_view_services()
    {
        $this->actingAsUser();

        $response = $this->get(route('services.index'));
        $response->assertStatus(200);
        $response->assertViewIs('services.index');
    }

    public function test_admin_can_view_services_management()
    {
        $this->actingAsAdmin();

        $response = $this->get(route('admin.services.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.services.index');
    }

    public function test_admin_can_create_service()
    {
        $this->actingAsAdmin();
        
        $category = Category::factory()->create();

        $serviceData = [
            'name' => 'Haircut',
            'description' => 'Professional haircut service',
            'price' => 50.00,
            'duration' => 30,
            'category_id' => $category->id
        ];

        $response = $this->post(route('admin.services.store'), $serviceData);
        $response->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseHas('services', $serviceData);
    }

    public function test_admin_can_update_service()
    {
        $this->actingAsAdmin();
        
        $service = Service::factory()->create();

        $updateData = [
            'name' => 'Updated Haircut',
            'price' => 60.00,
            'duration' => 45
        ];

        $response = $this->put(route('admin.services.update', $service), $updateData);
        $response->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseHas('services', $updateData);
    }

    public function test_admin_can_delete_service()
    {
        $this->actingAsAdmin();
        
        $service = Service::factory()->create();

        $response = $this->delete(route('admin.services.destroy', $service));
        $response->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }

    public function test_service_requires_category()
    {
        $this->actingAsAdmin();

        $serviceData = [
            'name' => 'Haircut',
            'description' => 'Professional haircut service',
            'price' => 50.00,
            'duration' => 30
        ];

        $response = $this->post(route('admin.services.store'), $serviceData);
        $response->assertSessionHasErrors('category_id');
    }
} 