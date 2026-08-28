<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class ManagerEmployeeController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewEmployees', User::class);

        $employees = User::query()
            ->where('role', 'employee')
            ->whereHas('projects', fn ($query) => $query->where('manager_id', auth()->id()))
            ->with([
                'projects' => fn ($query) => $query->where('manager_id', auth()->id()),
                'assignedTasks' => fn ($query) => $query
                    ->whereHas('project', fn ($projectQuery) => $projectQuery->where('manager_id', auth()->id()))
                    ->with('project')
                    ->latest(),
            ])
            ->orderBy('name')
            ->get();

        return view('manager.employees.index', compact('employees'));
    }
}
