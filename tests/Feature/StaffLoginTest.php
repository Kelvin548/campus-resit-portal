<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_examiner_can_login_via_staff_login(): void
    {
        $response = $this->post('/staff/login', [
            'email' => 'examiner@campus.edu',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('chief-examiner.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_examiner_can_login_via_main_login(): void
    {
        $response = $this->post('/login', [
            'login_identifier' => 'examiner@campus.edu',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('chief-examiner.dashboard'));
    }
}
