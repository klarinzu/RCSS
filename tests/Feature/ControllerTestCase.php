<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

abstract class ControllerTestCase extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a regular user
        $this->user = User::factory()->create([
            'role' => 'user'
        ]);

        // Create an admin user
        $this->admin = User::factory()->create([
            'role' => 'admin'
        ]);
    }

    protected function actingAsUser()
    {
        return $this->actingAs($this->user);
    }

    protected function actingAsAdmin()
    {
        return $this->actingAs($this->admin);
    }
} 