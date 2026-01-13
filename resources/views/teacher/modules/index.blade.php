@extends('layouts.teacher')

@section('title', 'My Modules')
@section('header', 'My Modules')

@section('content')

<!-- ================= HERO (MATCH DASHBOARD) ================= -->
<div class="mb-10">
    <div class="flex items-center justify-between
                px-5 py-5 rounded-2xl shadow-lg
                bg-gradient-to-r from-[#C7F000] via-[#8EE000] to-[#35B24A]
                text-white">

        <div>
            <h1 class="text-2xl font-bold">
                Assigned Modules 📚
            </h1>
            <p class="mt-1 text-white/90 text-sm">
                Modules you are currently teaching
            </p>
        </div>

        <div class="hidden md:flex items-center justify-center
                    w-12 h-12 rounded-full bg-white/20 text-xl">
            🎓
        </div>
    </div>
</div>

<!-- ================= MODULE CARDS ================= -->
@if($modules->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        @foreach($modules as $module)
            <div
                class="rounded-2xl bg-white border border-black/5
                       p-6 shadow
                       transition-all duration-300
                       hover:-translate-y-1 hover:shadow-lg">

                <!-- MODULE NAME -->
                <h3 class="text-lg font-bold text-slate-900">
                    {{ $module->name }}
                </h3>

                <!-- CODE -->
                <p class="mt-1 text-sm text-slate-500">
                    Code: {{ $module->code ?? 'N/A' }}
                </p>

                <!-- STUDENT COUNT -->
                <div class="mt-4 text-sm text-slate-600">
                    Active Students:
                    <span class="font-semibold text-slate-900">
                        {{ $module->students()->whereNull('completed_at')->count() }}
                    </span>
                </div>

                <!-- ACTION -->
                <div class="mt-6">
                    <a href="{{ route('teacher.modules.show', $module) }}"
                       class="inline-flex items-center gap-2
                              px-4 py-2 rounded-lg
                              bg-slate-900 text-white
                              hover:bg-slate-800 transition
                              text-sm shadow">
                        View Students →
                    </a>
                </div>

            </div>
        @endforeach

    </div>
@else
    <div class="rounded-xl bg-white p-6 text-slate-600 shadow border border-black/5">
        No modules assigned to you yet.
    </div>
@endif

@endsection
