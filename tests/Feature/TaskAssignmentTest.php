<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_assign_a_task_to_a_project_employee(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $employee = User::factory()->create(['role' => 'employee']);
        $project = Project::create([
            'name' => 'Task project',
            'manager_id' => $manager->id,
            'start_date' => '2026-09-01',
            'deadline' => '2026-09-30',
            'status' => 'active',
        ]);
        $project->members()->attach($employee);

        $this->actingAs($manager)->post(route('tasks.store'), [
            'project_id' => $project->id,
            'assigned_to' => $employee->id,
            'title' => 'Employee task',
            'priority' => 'medium',
            'status' => 'todo',
        ])->assertRedirectToRoute('projects.show', $project);

        $this->assertDatabaseHas('tasks', ['title' => 'Employee task', 'assigned_to' => $employee->id]);
    }

    public function test_manager_cannot_assign_a_task_to_a_non_member(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $employee = User::factory()->create(['role' => 'employee']);
        $project = Project::create([
            'name' => 'Restricted project',
            'manager_id' => $manager->id,
            'start_date' => '2026-09-01',
            'deadline' => '2026-09-30',
            'status' => 'active',
        ]);

        $this->actingAs($manager)->post(route('tasks.store'), [
            'project_id' => $project->id,
            'assigned_to' => $employee->id,
            'title' => 'Invalid employee task',
            'priority' => 'medium',
            'status' => 'todo',
        ])->assertSessionHasErrors('assigned_to');

        $this->assertDatabaseMissing('tasks', ['title' => 'Invalid employee task']);
    }
}