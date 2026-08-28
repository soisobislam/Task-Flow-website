<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_create_a_project(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);

        $response = $this->actingAs($manager)->post(route('projects.store'), [
            'name' => 'Launch planning',
            'description' => 'Coordinate the launch work.',
            'start_date' => '2026-09-01',
            'deadline' => '2026-09-30',
            'status' => 'planning',
        ]);

        $response->assertRedirectToRoute('projects.index');
        $this->assertDatabaseHas('projects', ['name' => 'Launch planning', 'manager_id' => $manager->id]);
    }

    public function test_employee_cannot_access_manager_project_area(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        $this->actingAs($employee)->get(route('projects.index'))->assertForbidden();
        $this->actingAs($employee)->get(route('projects.create'))->assertForbidden();
    }

    public function test_project_deadline_must_not_precede_start_date(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);

        $this->actingAs($manager)->post(route('projects.store'), [
            'name' => 'Invalid timeline',
            'start_date' => '2026-09-30',
            'deadline' => '2026-09-01',
            'status' => 'planning',
        ])->assertSessionHasErrors('deadline');

        $this->assertDatabaseCount('projects', 0);
    }

    public function test_manager_can_view_an_admin_created_project(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'manager']);
        $project = Project::create([
            'name' => 'Admin-created project',
            'manager_id' => $admin->id,
            'start_date' => '2026-09-01',
            'deadline' => '2026-09-30',
            'status' => 'active',
        ]);

        $this->actingAs($manager)->get(route('projects.index'))
            ->assertOk()
            ->assertSee('Admin-created project');

        $this->actingAs($manager)->get(route('projects.show', $project))->assertOk();
    }

    public function test_admin_can_assign_a_project_to_a_manager_and_add_employees(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'manager']);
        $employee = User::factory()->create(['role' => 'employee']);

        $this->actingAs($admin)->post(route('projects.store'), [
            'name' => 'Assigned project',
            'manager_id' => $manager->id,
            'members' => [$employee->id],
            'start_date' => '2026-09-01',
            'deadline' => '2026-09-30',
            'status' => 'planning',
        ])->assertRedirectToRoute('projects.index');

        $project = Project::where('name', 'Assigned project')->firstOrFail();
        $this->assertSame($manager->id, $project->manager_id);
        $this->assertTrue($project->members()->whereKey($employee->id)->exists());
    }
}
