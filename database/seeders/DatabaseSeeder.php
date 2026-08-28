<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create(['name' => 'Avery Admin', 'email' => 'admin@example.com', 'role' => 'admin', 'password' => Hash::make('password')]);
        $manager = User::factory()->create(['name' => 'Morgan Manager', 'email' => 'manager@example.com', 'role' => 'manager', 'password' => Hash::make('password')]);
        $employee = User::factory()->create(['name' => 'Elliot Employee', 'email' => 'employee@example.com', 'role' => 'employee', 'password' => Hash::make('password')]);

        $project = Project::create([
            'name' => 'Website refresh',
            'description' => 'A focused refresh of the public website experience.',
            'manager_id' => $manager->id,
            'start_date' => now()->toDateString(),
            'deadline' => now()->addMonth()->toDateString(),
            'status' => 'active',
        ]);

        $project->members()->attach([$employee->id]);
        Task::create(['project_id' => $project->id, 'assigned_to' => $employee->id, 'created_by' => $manager->id, 'title' => 'Audit current pages', 'description' => 'Capture the baseline before implementation.', 'priority' => 'high', 'status' => 'in_progress', 'deadline' => now()->addDays(7)->toDateString()]);
        Task::create(['project_id' => $project->id, 'created_by' => $manager->id, 'title' => 'Draft content map', 'description' => 'Outline the new information structure.', 'priority' => 'medium', 'status' => 'todo', 'deadline' => now()->addDays(14)->toDateString()]);
    }
}
