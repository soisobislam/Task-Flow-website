@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="mb-8"><p class="mb-2 text-sm font-medium uppercase tracking-[0.2em] text-cyan-300">New workspace</p><h1 class="text-4xl font-semibold tracking-normal">Create a project</h1><p class="mt-3 text-slate-400">Set the direction, timing, and current state for a new piece of work.</p></div>
    <form method="POST" action="{{ route('projects.store') }}" class="space-y-5 rounded-2xl border border-white/10 bg-white/5 p-6">
        @csrf
        <label class="block text-sm text-slate-300">Project name<input name="name" value="{{ old('name') }}" required class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-3 outline-none focus:ring-2 focus:ring-cyan-300"></label>
        <label class="block text-sm text-slate-300">Description<textarea name="description" rows="4" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-3 outline-none focus:ring-2 focus:ring-cyan-300">{{ old('description') }}</textarea></label>
        <div class="grid gap-5 sm:grid-cols-2"><label class="block text-sm text-slate-300">Start date<input name="start_date" type="date" value="{{ old('start_date') }}" required class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-3 outline-none focus:ring-2 focus:ring-cyan-300"></label><label class="block text-sm text-slate-300">Deadline<input name="deadline" type="date" value="{{ old('deadline') }}" required class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-3 outline-none focus:ring-2 focus:ring-cyan-300"></label></div>
        @if (auth()->user()->isAdmin())
            <label class="block text-sm text-slate-300">Assign manager<select name="manager_id" required class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-3 outline-none focus:ring-2 focus:ring-cyan-300"><option value="">Choose a manager</option>@foreach ($managers as $manager)<option value="{{ $manager->id }}" @selected(old('manager_id') == $manager->id)>{{ $manager->name }}</option>@endforeach</select></label>
        @else
            <input type="hidden" name="manager_id" value="{{ auth()->id() }}">
        @endif
        <label class="block text-sm text-slate-300">Status<select name="status" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-3 outline-none focus:ring-2 focus:ring-cyan-300"><option value="planning">Planning</option><option value="active">Active</option><option value="on_hold">On hold</option><option value="completed">Completed</option><option value="cancelled">Cancelled</option></select></label>
        <fieldset><legend class="text-sm text-slate-300">Project employees</legend><div class="mt-2 grid gap-2 sm:grid-cols-2">@forelse ($employees as $employee)<label class="flex items-center gap-2 rounded-lg border border-white/10 px-3 py-2 text-sm text-slate-500"><input type="checkbox" name="members[]" value="{{ $employee->id }}" @checked(in_array($employee->id, old('members', [])))>{{ $employee->name }}</label>@empty<p class="text-sm text-slate-500">No employees available.</p>@endforelse</div></fieldset>
        <div class="flex justify-end gap-3"><a href="{{ route('projects.index') }}" class="rounded-lg border border-white/15 px-4 py-3 text-slate-300">Cancel</a><button class="rounded-lg bg-cyan-300 px-4 py-3 font-semibold text-slate-950">Create project</button></div>
    </form>
</div>
@endsection
