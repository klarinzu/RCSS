<?php

namespace Tests\Unit;

use App\Models\Setting;

class SettingTest extends ModelTestCase
{
    public function test_setting_can_be_created()
    {
        $setting = Setting::factory()->create([
            'key' => 'business_name',
            'value' => 'Test Salon',
            'description' => 'The name of the business'
        ]);

        $this->assertInstanceOf(Setting::class, $setting);
        $this->assertEquals('business_name', $setting->key);
        $this->assertEquals('Test Salon', $setting->value);
        $this->assertEquals('The name of the business', $setting->description);
    }

    public function test_setting_has_required_attributes()
    {
        $setting = Setting::factory()->create();

        $this->assertNotNull($setting->key);
        $this->assertNotNull($setting->value);
    }

    public function test_setting_key_is_unique()
    {
        Setting::factory()->create(['key' => 'business_name']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Setting::factory()->create(['key' => 'business_name']);
    }
} 