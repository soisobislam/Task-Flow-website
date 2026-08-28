<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_managers_employees_and_assignments(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['name' => 'Project Manager', 'role' => 'manager']);
        $employee = User::factory()->create(['name' => 'Project Employee', 'role' => 'employee']);
        $project = Project::create([
            'name' => 'Launch project',
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
            'title' => 'Prepare launch checklist',
            'priority' => 'high',
            'status' => 'todo',
        ]);

        $this->actingAs($admin)->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Project Manager')
            ->assertSee('Project Employee')
            ->assertSee('Prepare launch checklist')
            ->assertSee('Launch project');
    }

    public function test_non_admin_cannot_view_the_admin_directory(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);

        $this->actingAs($manager)->get(route('admin.users.index'))->assertForbidden();
    }
}
