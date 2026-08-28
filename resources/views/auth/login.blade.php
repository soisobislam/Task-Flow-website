@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-md">
    <div class="mb-8">
        <p class="mb-2 text-sm font-medium uppercase tracking-[0.2em] text-cyan-300">Welcome back</p>
        <h1 class="text-4xl font-semibold tracking-normal">Sign in to TaskFlow</h1>
        <p class="mt-3 text-slate-400">Keep your projects moving and your team aligned.</p>
    </div>
    <form method="POST" action="{{ route('login.store') }}" class="space-y-5 rounded-2xl border border-white/10 bg-white/5 p-6 shadow-2xl shadow-cyan-950/30">
        @csrf
        <label class="block text-sm text-slate-300">Email<input name="email" type="email" value="{{ old('email') }}" required autofocus class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-3 text-slate-100 outline-none ring-cyan-300 focus:ring-2"></label>
        <label class="block text-sm text-slate-300">Password<input name="password" type="password" required class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-3 text-slate-100 outline-none ring-cyan-300 focus:ring-2"></label>
        <label class="flex items-center gap-2 text-sm text-slate-400"><input type="checkbox" name="remember" value="1" class="rounded border-white/20 bg-slate-900 text-cyan-400"> Remember me</label>
        <button class="w-full rounded-lg bg-cyan-300 px-4 py-3 font-semibold text-slate-950 transition hover:bg-cyan-200">Sign in</button>
    </form>
    <p class="mt-6 text-center text-sm text-slate-400">New to TaskFlow? <a href="{{ route('register') }}" class="text-cyan-300 hover:text-cyan-200">Create an account</a></p>
</div>
@endsection
