@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
    <div>
        <p class="mb-2 text-sm font-medium uppercase tracking-[0.2em] text-cyan-300">Workspace</p>
        <h1 class="text-4xl font-semibold tracking-normal">Projects</h1>
        <p class="mt-3 text-slate-400">A focused view of the work your team is moving forward.</p>
    </div>
    @can('create', App\Models\Project::class)
        <a href="{{ route('projects.create') }}" class="rounded-lg bg-cyan-300 px-4 py-3 font-semibold text-slate-950 transition hover:bg-cyan-200">New project</a>
    @endcan
</div>
<div class="overflow-hidden rounded-2xl border border-white/10 bg-white/5">
    @forelse ($projects as $project)
        <a href="{{ route('projects.show', $project) }}" class="block border-b border-white/10 p-5 transition last:border-b-0 hover:bg-white/5 sm:flex sm:items-center sm:justify-between">
            <div><h2 class="font-semibold text-white">{{ $project->name }}</h2><p class="mt-1 text-sm text-slate-400">Managed by {{ $project->manager->name }}</p>@if (auth()->user()->isManager() && $project->manager->isAdmin())<p class="mt-1 text-xs text-cyan-300">Admin-created project</p>@endif</div>
            <div class="mt-3 flex items-center gap-4 text-sm sm:mt-0"><span class="rounded-full bg-cyan-400/10 px-3 py-1 text-cyan-300">{{ str_replace('_', ' ', ucfirst($project->status)) }}</span><span class="text-slate-400">Due {{ $project->deadline->format('M j, Y') }}</span></div>
        </a>
    @empty
        <div class="p-10 text-center"><h2 class="text-lg font-semibold">No projects yet</h2><p class="mt-2 text-slate-400">Create the first project to give your team a shared direction.</p></div>
    @endforelse
</div>
<div class="mt-6">{{ $projects->links() }}</div>
@endsection
