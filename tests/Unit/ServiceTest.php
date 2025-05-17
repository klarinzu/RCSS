<?php

namespace Tests\Unit;

use App\Models\Service;
use App\Models\Category;

class ServiceTest extends ModelTestCase
{
    public function test_service_can_be_created()
    {
        $category = Category::factory()->create();
        
        $service = Service::factory()->create([
            'name' => 'Test Service',
            'description' => 'Test Description',
            'price' => 100.00,
            'category_id' => $category->id,
            'duration' => 60
        ]);

        $this->assertInstanceOf(Service::class, $service);
        $this->assertEquals('Test Service', $service->name);
        $this->assertEquals('Test Description', $service->description);
        $this->assertEquals(100.00, $service->price);
        $this->assertEquals($category->id, $service->category_id);
        $this->assertEquals(60, $service->duration);
    }

    public function test_service_has_required_attributes()
    {
        $service = Service::factory()->create();

        $this->assertNotNull($service->name);
        $this->assertNotNull($service->description);
        $this->assertNotNull($service->price);
        $this->assertNotNull($service->category_id);
        $this->assertNotNull($service->duration);
    }

    public function test_service_belongs_to_category()
    {
        $category = Category::factory()->create();
        $service = Service::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $service->category);
        $this->assertEquals($category->id, $service->category->id);
    }
} 