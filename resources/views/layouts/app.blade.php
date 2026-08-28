<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'TaskFlow' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="flex min-h-screen flex-col bg-[radial-gradient(circle_at_top_right,_#164e63_0,_transparent_35%),linear-gradient(145deg,_#020617,_#0f172a)]">
        @include('layouts.header')
        @auth
            <div class="drawer-backdrop" data-drawer-close></div>
            <aside id="taskflow-drawer" class="taskflow-drawer" aria-label="TaskFlow navigation" aria-hidden="true">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-cyan-300">Signed in as</p>
                        <p class="mt-2 text-lg font-semibold">{{ auth()->user()->name }}</p>
                        <span class="mt-2 inline-block rounded-full bg-cyan-400/10 px-3 py-1 text-xs text-cyan-300">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                    <button type="button" class="drawer-close p-2" data-drawer-close aria-label="Close navigation menu">
                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="m6 6 12 12M18 6 6 18" stroke-linecap="round"/></svg>
                    </button>
                </div>
                <nav class="mt-10 space-y-2" aria-label="Primary navigation">
                    <a href="{{ route('dashboard') }}" class="drawer-link">Dashboard</a>
                    @can('viewAny', App\Models\Project::class)
                        <a href="{{ route('projects.index') }}" class="drawer-link">Projects</a>
                    @endcan
                    <a href="{{ route('tasks.index') }}" class="drawer-link">Tasks</a>
                    @can('viewAny', App\Models\User::class)
                        <a href="{{ route('admin.users.index') }}" class="drawer-link">People</a>
                    @endcan
                    @can('viewEmployees', App\Models\User::class)
                        <a href="{{ route('manager.employees.index') }}" class="drawer-link">People</a>
                    @endcan
                </nav>
                <form method="POST" action="{{ route('logout') }}" class="mt-auto pt-10">
                    @csrf
                    <button class="w-full px-4 py-3 text-left">Sign out</button>
                </form>
            </aside>
        @endauth
        <main class="mx-auto max-w-7xl px-6 py-10">
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-emerald-400/30 bg-emerald-400/10 px-4 py-3 text-emerald-200">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-rose-400/30 bg-rose-400/10 px-4 py-3 text-rose-200">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
        @include('layouts.footer')
    </div>
</body>
</html>
