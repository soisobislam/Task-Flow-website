@extends('layouts.app')

@section('content')
<div class="mb-8">
    <p class="mb-2 text-sm font-medium uppercase tracking-[0.2em] text-cyan-300">Team workspace</p>
    <h1 class="text-4xl font-semibold tracking-normal">My employees</h1>
    <p class="mt-3 max-w-2xl text-slate-400">Employees assigned to projects you manage and the tasks currently on their plates.</p>
</div>

<div class="overflow-hidden rounded-2xl border border-white/10 bg-white/5">
    @forelse ($employees as $employee)
        <article class="border-b border-white/10 p-6 last:border-b-0">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                <div>
                    <h2 class="text-lg font-semibold text-white">{{ $employee->name }}</h2>
                    <p class="mt-1 text-sm text-slate-400">{{ $employee->email }}</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($employee->projects as $project)
                            <a href="{{ route('projects.show', $project) }}" class="rounded-full border border-white/10 px-3 py-1 text-xs text-slate-300 hover:border-cyan-300/50">{{ $project->name }}</a>
                        @endforeach
                    </div>
                </div>
                <span class="rounded-full bg-cyan-400/10 px-3 py-1 text-sm text-cyan-300">{{ $employee->assignedTasks->count() }} {{ Str::plural('task', $employee->assignedTasks->count()) }}</span>
            </div>
            <div class="mt-5 grid gap-3 md:grid-cols-2">
                @forelse ($employee->assignedTasks as $task)
                    <a href="{{ route('tasks.show', $task) }}" class="rounded-lg border border-white/10 bg-slate-900/50 px-4 py-3 transition hover:border-cyan-300/50">
                        <div class="flex items-center justify-between gap-3">
                            <span class="font-medium text-slate-200">{{ $task->title }}</span>
                            <span class="shrink-0 text-xs text-cyan-300">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">{{ $task->project->name }}@if ($task->deadline) · Due {{ $task->deadline->format('M j, Y') }}@endif</p>
                    </a>
                @empty
                    <p class="text-sm text-slate-500">No tasks assigned in your projects.</p>
                @endforelse
            </div>
        </article>
    @empty
        <div class="p-10 text-center">
            <h2 class="text-lg font-semibold">No employees found</h2>
            <p class="mt-2 text-slate-400">Employees will appear here after they are added to one of your projects.</p>
        </div>
    @endforelse
</div>
@endsection
