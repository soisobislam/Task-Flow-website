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
        $admin = User::updateOrCreate(['email' => 'admin@example.com'], ['name' => 'Avery Admin', 'role' => 'admin', 'password' => Hash::make('password')]);
        $manager = User::updateOrCreate(['email' => 'manager@example.com'], ['name' => 'Morgan Manager', 'role' => 'manager', 'password' => Hash::make('password')]);
        $employee = User::updateOrCreate(['email' => 'employee@example.com'], ['name' => 'Elliot Employee', 'role' => 'employee', 'password' => Hash::make('password')]);

        $project = Project::firstOrCreate(['name' => 'Website refresh'], [
            'name' => 'Website refresh',
            'description' => 'A focused refresh of the public website experience.',
            'manager_id' => $manager->id,
            'start_date' => now()->toDateString(),
            'deadline' => now()->addMonth()->toDateString(),
            'status' => 'active',
        ]);

        $project->members()->syncWithoutDetaching([$employee->id]);
        Task::firstOrCreate(['project_id' => $project->id, 'title' => 'Audit current pages'], ['assigned_to' => $employee->id, 'created_by' => $manager->id, 'description' => 'Capture the baseline before implementation.', 'priority' => 'high', 'status' => 'in_progress', 'deadline' => now()->addDays(7)->toDateString()]);
        Task::firstOrCreate(['project_id' => $project->id, 'title' => 'Draft content map'], ['created_by' => $manager->id, 'description' => 'Outline the new information structure.', 'priority' => 'medium', 'status' => 'todo', 'deadline' => now()->addDays(14)->toDateString()]);
    }
}
