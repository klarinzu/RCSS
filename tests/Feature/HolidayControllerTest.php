<?php

namespace Tests\Feature;

use App\Models\Holiday;

class HolidayControllerTest extends ControllerTestCase
{
    public function test_admin_can_view_holidays()
    {
        $this->actingAsAdmin();

        $response = $this->get(route('admin.holidays.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.holidays.index');
    }

    public function test_admin_can_create_holiday()
    {
        $this->actingAsAdmin();

        $holidayData = [
            'name' => 'Christmas',
            'date' => '2024-12-25',
            'description' => 'Christmas Day'
        ];

        $response = $this->post(route('admin.holidays.store'), $holidayData);
        $response->assertRedirect(route('admin.holidays.index'));
        $this->assertDatabaseHas('holidays', $holidayData);
    }

    public function test_admin_can_update_holiday()
    {
        $this->actingAsAdmin();
        
        $holiday = Holiday::factory()->create();

        $updateData = [
            'name' => 'Updated Holiday',
            'date' => '2024-12-26',
            'description' => 'Updated description'
        ];

        $response = $this->put(route('admin.holidays.update', $holiday), $updateData);
        $response->assertRedirect(route('admin.holidays.index'));
        $this->assertDatabaseHas('holidays', $updateData);
    }

    public function test_admin_can_delete_holiday()
    {
        $this->actingAsAdmin();
        
        $holiday = Holiday::factory()->create();

        $response = $this->delete(route('admin.holidays.destroy', $holiday));
        $response->assertRedirect(route('admin.holidays.index'));
        $this->assertDatabaseMissing('holidays', ['id' => $holiday->id]);
    }

    public function test_holiday_requires_name_and_date()
    {
        $this->actingAsAdmin();

        $holidayData = [
            'description' => 'Test description'
        ];

        $response = $this->post(route('admin.holidays.store'), $holidayData);
        $response->assertSessionHasErrors(['name', 'date']);
    }

    public function test_holiday_date_must_be_unique()
    {
        $this->actingAsAdmin();
        
        Holiday::factory()->create(['date' => '2024-12-25']);

        $holidayData = [
            'name' => 'Another Holiday',
            'date' => '2024-12-25',
            'description' => 'Test description'
        ];

        $response = $this->post(route('admin.holidays.store'), $holidayData);
        $response->assertSessionHasErrors('date');
    }
} 