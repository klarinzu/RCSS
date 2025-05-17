<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Models\User;

class EmployeeTest extends ModelTestCase
{
    public function test_employee_can_be_created()
    {
        $user = User::factory()->create();
        
        $employee = Employee::factory()->create([
            'user_id' => $user->id,
            'position' => 'Senior Stylist',
            'bio' => 'Experienced stylist with 5 years of experience',
            'status' => 'active'
        ]);

        $this->assertInstanceOf(Employee::class, $employee);
        $this->assertEquals($user->id, $employee->user_id);
        $this->assertEquals('Senior Stylist', $employee->position);
        $this->assertEquals('Experienced stylist with 5 years of experience', $employee->bio);
        $this->assertEquals('active', $employee->status);
    }

    public function test_employee_has_required_attributes()
    {
        $employee = Employee::factory()->create();

        $this->assertNotNull($employee->user_id);
        $this->assertNotNull($employee->position);
        $this->assertNotNull($employee->status);
    }

    public function test_employee_belongs_to_user()
    {
        $user = User::factory()->create();
        $employee = Employee::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $employee->user);
        $this->assertEquals($user->id, $employee->user->id);
    }
} 