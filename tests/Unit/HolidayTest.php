<?php

namespace Tests\Unit;

use App\Models\Holiday;

class HolidayTest extends ModelTestCase
{
    public function test_holiday_can_be_created()
    {
        $holiday = Holiday::factory()->create([
            'name' => 'Christmas',
            'date' => '2024-12-25',
            'description' => 'Christmas Day'
        ]);

        $this->assertInstanceOf(Holiday::class, $holiday);
        $this->assertEquals('Christmas', $holiday->name);
        $this->assertEquals('2024-12-25', $holiday->date);
        $this->assertEquals('Christmas Day', $holiday->description);
    }

    public function test_holiday_has_required_attributes()
    {
        $holiday = Holiday::factory()->create();

        $this->assertNotNull($holiday->name);
        $this->assertNotNull($holiday->date);
        $this->assertNotNull($holiday->description);
    }

    public function test_holiday_date_is_unique()
    {
        Holiday::factory()->create(['date' => '2024-12-25']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Holiday::factory()->create(['date' => '2024-12-25']);
    }
} 