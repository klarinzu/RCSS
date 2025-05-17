<?php

namespace Tests\Unit;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use App\Models\Employee;

class AppointmentTest extends ModelTestCase
{
    public function test_appointment_can_be_created()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create();
        $employee = Employee::factory()->create();

        $appointment = Appointment::factory()->create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'employee_id' => $employee->id,
            'date' => '2024-03-20',
            'time' => '10:00:00',
            'status' => 'pending'
        ]);

        $this->assertInstanceOf(Appointment::class, $appointment);
        $this->assertEquals($user->id, $appointment->user_id);
        $this->assertEquals($service->id, $appointment->service_id);
        $this->assertEquals($employee->id, $appointment->employee_id);
    }

    public function test_appointment_has_required_attributes()
    {
        $appointment = Appointment::factory()->create();

        $this->assertNotNull($appointment->user_id);
        $this->assertNotNull($appointment->service_id);
        $this->assertNotNull($appointment->employee_id);
        $this->assertNotNull($appointment->date);
        $this->assertNotNull($appointment->time);
        $this->assertNotNull($appointment->status);
    }

    public function test_appointment_belongs_to_user()
    {
        $user = User::factory()->create();
        $appointment = Appointment::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $appointment->user);
        $this->assertEquals($user->id, $appointment->user->id);
    }
} 