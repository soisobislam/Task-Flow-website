<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(): View
    {
        $tasks = auth()->user()->isAdmin()
            ? Task::with(['project', 'assignee'])->latest()->paginate(15)
            : Task::with(['project', 'assignee'])->where('created_by', auth()->id())->orWhere('assigned_to', auth()->id())->latest()->paginate(15);

        return view('tasks.index', compact('tasks'));
    }

    public function create(Project $project): View
    {
        $this->authorize('create', [Task::class, new Task(['project_id' => $project->id])]);
        $project->load(['members' => fn ($query) => $query->where('role', 'employee')]);

        return view('tasks.create', compact('project'));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $task = Task::create($request->validated() + ['created_by' => $request->user()->id]);

        return redirect()->route('projects.show', $task->project)->with('success', 'Task created successfully.');
    }

    public function show(Task $task): View
    {
        $this->authorize('view', $task);
        $task->load(['project', 'assignee', 'creator']);

        return view('tasks.show', compact('task'));
    }

    public function updateStatus(Task $task, string $status): RedirectResponse
    {
        abort_unless(in_array($status, ['todo', 'in_progress', 'review', 'completed'], true), 404);
        $this->authorize('update', $task);
        $task->update(['status' => $status]);

        return back()->with('success', 'Task status updated.');
    }
}
