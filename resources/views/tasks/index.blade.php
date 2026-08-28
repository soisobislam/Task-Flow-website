@extends('layouts.app')

@section('content')
<div class="mb-8"><p class="mb-2 text-sm font-medium uppercase tracking-[0.2em] text-cyan-300">Work queue</p><h1 class="text-4xl font-semibold tracking-normal">Tasks</h1><p class="mt-3 text-slate-400">Keep the next actions visible and moving.</p></div>
<div class="overflow-hidden rounded-2xl border border-white/10 bg-white/5">@forelse ($tasks as $task)<a href="{{ route('tasks.show', $task) }}" class="block border-b border-white/10 p-5 last:border-b-0 hover:bg-white/5"><div class="flex flex-col justify-between gap-2 sm:flex-row"><div><h2 class="font-semibold">{{ $task->title }}</h2><p class="mt-1 text-sm text-slate-400">{{ $task->project->name }} · {{ $task->assignee?->name ?: 'Unassigned' }}</p></div><div class="flex gap-3 text-sm"><span class="text-amber-200">{{ ucfirst($task->priority) }}</span><span class="text-cyan-300">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span></div></div></a>@empty<div class="p-10 text-center text-slate-400">No tasks found.</div>@endforelse</div><div class="mt-6">{{ $tasks->links() }}</div>
@endsection
