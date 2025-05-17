<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;

class EmployeeControllerTest extends ControllerTestCase
{
    public function test_user_can_view_employees()
    {
        $this->actingAsUser();

        $response = $this->get(route('employees.index'));
        $response->assertStatus(200);
        $response->assertViewIs('employees.index');
    }

    public function test_admin_can_view_employees_management()
    {
        $this->actingAsAdmin();

        $response = $this->get(route('admin.employees.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.employees.index');
    }

    public function test_admin_can_create_employee()
    {
        $this->actingAsAdmin();
        
        $user = User::factory()->create();

        $employeeData = [
            'user_id' => $user->id,
            'position' => 'Senior Stylist',
            'bio' => 'Experienced stylist with 5 years of experience',
            'status' => 'active'
        ];

        $response = $this->post(route('admin.employees.store'), $employeeData);
        $response->assertRedirect(route('admin.employees.index'));
        $this->assertDatabaseHas('employees', $employeeData);
    }

    public function test_admin_can_update_employee()
    {
        $this->actingAsAdmin();
        
        $employee = Employee::factory()->create();

        $updateData = [
            'position' => 'Master Stylist',
            'bio' => 'Updated bio',
            'status' => 'inactive'
        ];

        $response = $this->put(route('admin.employees.update', $employee), $updateData);
        $response->assertRedirect(route('admin.employees.index'));
        $this->assertDatabaseHas('employees', $updateData);
    }

    public function test_admin_can_delete_employee()
    {
        $this->actingAsAdmin();
        
        $employee = Employee::factory()->create();

        $response = $this->delete(route('admin.employees.destroy', $employee));
        $response->assertRedirect(route('admin.employees.index'));
        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }

    public function test_employee_requires_user()
    {
        $this->actingAsAdmin();

        $employeeData = [
            'position' => 'Senior Stylist',
            'bio' => 'Experienced stylist',
            'status' => 'active'
        ];

        $response = $this->post(route('admin.employees.store'), $employeeData);
        $response->assertSessionHasErrors('user_id');
    }
} 