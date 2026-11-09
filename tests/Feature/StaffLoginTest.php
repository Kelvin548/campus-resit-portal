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

    public function test_admin_can_login_and_see_felicia_admin_navigation(): void
    {
        $response = $this->post('/staff/login', [
            'email' => 'admin@campus.edu',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();

        $dashboardResponse = $this->get(route('admin.dashboard'));
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee(route('admin.dashboard'));
        $dashboardResponse->assertSee(route('admin.students.index'));
        $dashboardResponse->assertSee(route('admin.staff-invitations.create'));
        $dashboardResponse->assertSee('Create Invitation');
        $dashboardResponse->assertSee('Students');
        $dashboardResponse->assertSee('Administrator');
        $dashboardResponse->assertDontSee(route('chief-examiner.timetables.index'));
        $dashboardResponse->assertDontSee(route('chief-examiner.broadcasts.index'));
        $dashboardResponse->assertDontSee(route('chief-examiner.cycle-settings.edit'));
    }
}
