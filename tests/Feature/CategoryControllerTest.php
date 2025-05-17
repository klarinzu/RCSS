<?php

namespace Tests\Feature;

use App\Models\Category;

class CategoryControllerTest extends ControllerTestCase
{
    public function test_user_can_view_categories()
    {
        $this->actingAsUser();

        $response = $this->get(route('categories.index'));
        $response->assertStatus(200);
        $response->assertViewIs('categories.index');
    }

    public function test_admin_can_view_categories_management()
    {
        $this->actingAsAdmin();

        $response = $this->get(route('admin.categories.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
    }

    public function test_admin_can_create_category()
    {
        $this->actingAsAdmin();

        $categoryData = [
            'name' => 'Hair Services',
            'description' => 'All hair-related services'
        ];

        $response = $this->post(route('admin.categories.store'), $categoryData);
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', $categoryData);
    }

    public function test_admin_can_update_category()
    {
        $this->actingAsAdmin();
        
        $category = Category::factory()->create();

        $updateData = [
            'name' => 'Updated Hair Services',
            'description' => 'Updated description'
        ];

        $response = $this->put(route('admin.categories.update', $category), $updateData);
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', $updateData);
    }

    public function test_admin_can_delete_category()
    {
        $this->actingAsAdmin();
        
        $category = Category::factory()->create();

        $response = $this->delete(route('admin.categories.destroy', $category));
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_category_requires_name()
    {
        $this->actingAsAdmin();

        $categoryData = [
            'description' => 'Test description'
        ];

        $response = $this->post(route('admin.categories.store'), $categoryData);
        $response->assertSessionHasErrors('name');
    }
} 