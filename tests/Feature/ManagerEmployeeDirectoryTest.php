<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagerEmployeeDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_view_employees_in_their_projects_and_assigned_tasks(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $employee = User::factory()->create(['name' => 'Assigned Employee', 'role' => 'employee']);
        $project = Project::create([
            'name' => 'Manager project',
            'manager_id' => $manager->id,
            'start_date' => '2026-09-01',
            'deadline' => '2026-09-30',
            'status' => 'active',
        ]);
        $project->members()->attach($employee);
        Task::create([
            'project_id' => $project->id,
            'assigned_to' => $employee->id,
            'created_by' => $manager->id,
            'title' => 'Assigned manager task',
            'priority' => 'medium',
            'status' => 'in_progress',
        ]);

        $this->actingAs($manager)->get(route('manager.employees.index'))
            ->assertOk()
            ->assertSee('Assigned Employee')
            ->assertSee('Manager project')
            ->assertSee('Assigned manager task');
    }

    public function test_manager_cannot_see_employees_from_another_managers_project(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $otherManager = User::factory()->create(['role' => 'manager']);
        $employee = User::factory()->create(['name' => 'Other Employee', 'role' => 'employee']);
        $project = Project::create([
            'name' => 'Other manager project',
            'manager_id' => $otherManager->id,
            'start_date' => '2026-09-01',
            'deadline' => '2026-09-30',
            'status' => 'active',
        ]);
        $project->members()->attach($employee);

        $this->actingAs($manager)->get(route('manager.employees.index'))
            ->assertOk()
            ->assertDontSee('Other Employee')
            ->assertDontSee('Other manager project');
    }

    public function test_employee_cannot_access_manager_employee_directory(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        $this->actingAs($employee)->get(route('manager.employees.index'))->assertForbidden();
    }
}
