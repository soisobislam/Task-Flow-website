@extends('layouts.app')

@section('content')
<div class="mb-8">
    <p class="mb-2 text-sm font-medium uppercase tracking-[0.2em] text-cyan-300">Admin directory</p>
    <h1 class="text-4xl font-semibold tracking-normal">People and assignments</h1>
    <p class="mt-3 max-w-2xl text-slate-400">See who is leading projects, who is contributing, and the work currently assigned to each person.</p>
</div>

<div class="grid gap-6 xl:grid-cols-2">
    @foreach ([['Managers', $managers, 'manager'], ['Employees', $employees, 'employee']] as [$heading, $users, $role])
        <section class="rounded-2xl border border-white/10 bg-white/5 p-6">
            <div class="mb-5 flex items-center justify-between border-b border-white/10 pb-4">
                <h2 class="text-xl font-semibold">{{ $heading }}</h2>
                <span class="rounded-full bg-cyan-400/10 px-3 py-1 text-sm text-cyan-300">{{ $users->count() }}</span>
            </div>
            <div class="space-y-5">
                @forelse ($users as $user)
                    <article class="border-b border-white/10 pb-5 last:border-0 last:pb-0">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="font-semibold text-white">{{ $user->name }}</h3>
                                <p class="mt-1 text-sm text-slate-400">{{ $user->email }}</p>
                            </div>
                            <span class="text-xs uppercase tracking-wider text-slate-500">{{ ucfirst($role) }}</span>
                        </div>
                        <div class="mt-4">
                            <p class="mb-2 text-xs font-medium uppercase tracking-wider text-slate-500">Assigned tasks</p>
                            @forelse ($user->assignedTasks as $task)
                                <a href="{{ route('tasks.show', $task) }}" class="mb-2 block rounded-lg border border-white/10 bg-slate-900/50 px-3 py-2 transition hover:border-cyan-300/50">
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-sm text-slate-200">{{ $task->title }}</span>
                                        <span class="shrink-0 text-xs text-cyan-300">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500">{{ $task->project->name }}</p>
                                </a>
                            @empty
                                <p class="text-sm text-slate-500">No tasks assigned.</p>
                            @endforelse
                        </div>
                    </article>
                @empty
                    <p class="py-6 text-center text-sm text-slate-400">No {{ strtolower($heading) }} found.</p>
                @endforelse
            </div>
        </section>
    @endforeach
</div>
@endsection
