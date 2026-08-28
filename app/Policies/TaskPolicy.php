<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        return $user->isAdmin() || $task->project->manager_id === $user->id || $task->assigned_to === $user->id;
    }

    public function create(User $user, Task $task): bool
    {
        return $user->isAdmin() || $task->project->manager_id === $user->id;
    }

    public function update(User $user, Task $task): bool
    {
        return $user->isAdmin() || $task->project->manager_id === $user->id || $task->assigned_to === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->isAdmin() || $task->project->manager_id === $user->id;
    }
}
