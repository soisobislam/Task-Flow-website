<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Project::class);
        $projects = auth()->user()->isAdmin()
            ? Project::with('manager')->latest()->paginate(10)
            : Project::with('manager')
                ->where(function ($query) {
                    $query->where('manager_id', auth()->id())
                        ->orWhereHas('manager', fn ($managerQuery) => $managerQuery->where('role', 'admin'));
                })
                ->latest()
                ->paginate(10);

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        $this->authorize('create', Project::class);
        $managers = User::where('role', 'manager')->orderBy('name')->get();
        $employees = User::where('role', 'employee')->orderBy('name')->get();

        return view('projects.create', compact('managers', 'employees'));
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $memberIds = $validated['members'] ?? [];
        unset($validated['members']);
        $project = Project::create($validated + ['manager_id' => $request->user()->id]);
        $project->members()->sync($memberIds);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project): View
    {
        $this->authorize('view', $project);
        $project->load(['manager', 'members', 'tasks.assignee']);

        return view('projects.show', compact('project'));
    }
}
