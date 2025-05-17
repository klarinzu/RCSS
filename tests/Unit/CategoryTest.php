<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Service;

class CategoryTest extends ModelTestCase
{
    public function test_category_can_be_created()
    {
        $category = Category::factory()->create([
            'name' => 'Hair Services',
            'description' => 'All hair-related services'
        ]);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Hair Services', $category->name);
        $this->assertEquals('All hair-related services', $category->description);
    }

    public function test_category_has_required_attributes()
    {
        $category = Category::factory()->create();

        $this->assertNotNull($category->name);
        $this->assertNotNull($category->description);
    }

    public function test_category_has_many_services()
    {
        $category = Category::factory()->create();
        $services = Service::factory()->count(3)->create(['category_id' => $category->id]);

        $this->assertCount(3, $category->services);
        $this->assertInstanceOf(Service::class, $category->services->first());
    }
} 