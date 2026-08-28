@extends('layouts.app')

@section('content')
<div class="mb-10">
    <p class="mb-2 text-sm font-medium uppercase tracking-[0.2em] text-cyan-300">{{ ucfirst(auth()->user()->role) }} workspace</p>
    <h1 class="text-4xl font-semibold tracking-normal">Good to see you, {{ auth()->user()->name }}.</h1>
    <p class="mt-3 max-w-2xl text-slate-400">Your role-aware workspace is ready. Start by opening the projects area when you are ready to organize work.</p>
    @can('viewAny', App\Models\Project::class)
        <a href="{{ route('projects.index') }}" class="mt-6 inline-flex rounded-lg bg-cyan-300 px-4 py-3 font-semibold text-slate-950 transition hover:bg-cyan-200">Open projects</a>
    @endcan
    @can('viewAny', App\Models\User::class)
        <a href="{{ route('admin.users.index') }}" class="mt-6 inline-flex rounded-lg border border-cyan-300/40 px-4 py-3 font-semibold text-cyan-200 transition hover:border-cyan-200">View people</a>
    @endcan
    @if (auth()->user()->isManager())
        <a href="{{ route('manager.employees.index') }}" class="mt-6 inline-flex rounded-lg border border-cyan-300/40 px-4 py-3 font-semibold text-cyan-200 transition hover:border-cyan-200">View employees</a>
    @endif
</div>
<div class="grid gap-5 md:grid-cols-3">
    <div class="rounded-2xl border border-white/10 bg-white/5 p-6"><p class="text-sm text-slate-400">Role</p><p class="mt-3 text-2xl font-semibold text-cyan-200">{{ ucfirst(auth()->user()->role) }}</p></div>
    <div class="rounded-2xl border border-white/10 bg-white/5 p-6"><p class="text-sm text-slate-400">Account status</p><p class="mt-3 text-2xl font-semibold text-emerald-300">Active</p></div>
    <div class="rounded-2xl border border-white/10 bg-white/5 p-6"><p class="text-sm text-slate-400">Next milestone</p><p class="mt-3 text-2xl font-semibold text-amber-200">Projects</p></div>
</div>
@endsection
