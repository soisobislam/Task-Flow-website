<header class="sticky top-0 z-20 border-b border-white/10 bg-slate-950/70 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-6 py-4">
        <div class="flex items-center gap-4">
            @auth
                <button type="button" class="drawer-toggle p-3" data-drawer-open aria-controls="taskflow-drawer" aria-expanded="false" aria-label="Open navigation menu">
                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round"/></svg>
                </button>
            @endauth
            <a href="{{ route('dashboard') }}" class="text-xl font-semibold tracking-normal text-cyan-300">TaskFlow</a>
        </div>
        @auth
            <div class="flex items-center gap-3 text-right">
                <div class="max-w-[9rem] sm:max-w-none">
                    <p class="truncate font-semibold leading-tight">{{ auth()->user()->name }}</p>
                    <p class="mt-1 text-xs uppercase tracking-wider text-slate-400">{{ ucfirst(auth()->user()->role) }}</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-cyan-400/10 font-semibold text-cyan-300" aria-hidden="true">
                    {{ collect(explode(' ', auth()->user()->name))->map(fn ($name) => strtoupper(substr($name, 0, 1)))->take(2)->join('') }}
                </div>
            </div>
        @endauth
    </div>
</header>
