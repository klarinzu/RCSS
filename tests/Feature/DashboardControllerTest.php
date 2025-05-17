<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;

class DashboardControllerTest extends ControllerTestCase
{
    public function test_admin_can_view_dashboard()
    {
        $this->actingAsAdmin();

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    public function test_dashboard_shows_today_appointments()
    {
        $this->actingAsAdmin();
        
        $today = Carbon::today();
        $appointments = Appointment::factory()->count(3)->create([
            'date' => $today->format('Y-m-d')
        ]);

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertViewHas('todayAppointments', function ($viewAppointments) use ($appointments) {
            return $viewAppointments->count() === $appointments->count();
        });
    }

    public function test_dashboard_shows_upcoming_appointments()
    {
        $this->actingAsAdmin();
        
        $futureDate = Carbon::today()->addDays(7);
        $appointments = Appointment::factory()->count(3)->create([
            'date' => $futureDate->format('Y-m-d')
        ]);

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertViewHas('upcomingAppointments', function ($viewAppointments) use ($appointments) {
            return $viewAppointments->count() === $appointments->count();
        });
    }

    public function test_dashboard_shows_recent_users()
    {
        $this->actingAsAdmin();
        
        $users = User::factory()->count(5)->create();

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertViewHas('recentUsers', function ($viewUsers) use ($users) {
            return $viewUsers->count() === $users->count();
        });
    }

    public function test_dashboard_shows_popular_services()
    {
        $this->actingAsAdmin();
        
        $service = Service::factory()->create();
        Appointment::factory()->count(5)->create([
            'service_id' => $service->id
        ]);

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertViewHas('popularServices', function ($viewServices) {
            return $viewServices->count() > 0;
        });
    }

    public function test_dashboard_shows_appointment_statistics()
    {
        $this->actingAsAdmin();
        
        Appointment::factory()->count(5)->create(['status' => 'pending']);
        Appointment::factory()->count(3)->create(['status' => 'confirmed']);
        Appointment::factory()->count(2)->create(['status' => 'cancelled']);

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertViewHas('statistics', function ($stats) {
            return isset($stats['pending']) && 
                   isset($stats['confirmed']) && 
                   isset($stats['cancelled']);
        });
    }
} 