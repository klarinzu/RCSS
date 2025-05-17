<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\Employee;
use App\Models\User;

class AppointmentControllerTest extends ControllerTestCase
{
    public function test_user_can_view_appointments()
    {
        $this->actingAsUser();

        $response = $this->get(route('appointments.index'));
        $response->assertStatus(200);
        $response->assertViewIs('appointments.index');
    }

    public function test_user_can_create_appointment()
    {
        $this->actingAsUser();
        
        $service = Service::factory()->create();
        $employee = Employee::factory()->create();

        $appointmentData = [
            'service_id' => $service->id,
            'employee_id' => $employee->id,
            'date' => '2024-03-20',
            'time' => '10:00:00',
            'notes' => 'Test appointment'
        ];

        $response = $this->post(route('appointments.store'), $appointmentData);
        $response->assertRedirect(route('appointments.index'));
        $this->assertDatabaseHas('appointments', $appointmentData);
    }

    public function test_user_can_update_appointment()
    {
        $this->actingAsUser();
        
        $appointment = Appointment::factory()->create([
            'user_id' => $this->user->id
        ]);

        $updateData = [
            'date' => '2024-03-21',
            'time' => '11:00:00',
            'notes' => 'Updated appointment'
        ];

        $response = $this->put(route('appointments.update', $appointment), $updateData);
        $response->assertRedirect(route('appointments.index'));
        $this->assertDatabaseHas('appointments', $updateData);
    }

    public function test_user_can_cancel_appointment()
    {
        $this->actingAsUser();
        
        $appointment = Appointment::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);

        $response = $this->delete(route('appointments.destroy', $appointment));
        $response->assertRedirect(route('appointments.index'));
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'cancelled'
        ]);
    }

    public function test_admin_can_view_all_appointments()
    {
        $this->actingAsAdmin();

        $response = $this->get(route('admin.appointments.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.appointments.index');
    }

    public function test_admin_can_update_appointment_status()
    {
        $this->actingAsAdmin();
        
        $appointment = Appointment::factory()->create([
            'status' => 'pending'
        ]);

        $response = $this->put(route('admin.appointments.update', $appointment), [
            'status' => 'confirmed'
        ]);

        $response->assertRedirect(route('admin.appointments.index'));
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'confirmed'
        ]);
    }
} 