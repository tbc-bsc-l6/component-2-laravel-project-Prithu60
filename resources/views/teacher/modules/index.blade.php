@extends('layouts.teacher')

@section('title', 'My Modules')
@section('header', 'My Modules')

@section('content')

<!-- ================= TITLE ================= -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-900">
        Assigned Modules
    </h2>
    <p class="text-slate-600">
        Modules you are currently teaching
    </p>
</div>

<!-- ================= MODULE CARDS ================= -->
@if($modules->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        @foreach($modules as $module)
            <div class="rounded-2xl bg-white shadow border border-black/5 p-6
                        hover:shadow-md transition">

                <h3 class="text-lg font-bold text-slate-900">
                    {{ $module->name }}
                </h3>

                <p class="mt-1 text-sm text-slate-600">
                    Code: {{ $module->code ?? 'N/A' }}
                </p>

                <div class="mt-4 text-sm text-slate-500">
                    Active Students:
                    <span class="font-semibold text-slate-800">
                        {{ $module->students()->whereNull('completed_at')->count() }}
                    </span>
                </div>

                <div class="mt-6">
                    <a href="{{ route('teacher.modules.show', $module) }}"
                       class="inline-flex items-center gap-2
                              px-4 py-2 rounded-lg
                              bg-slate-900 text-white
                              hover:bg-slate-800 transition text-sm">
                        View Students →
                    </a>
                </div>
            </div>
        @endforeach

    </div>
@else
    <div class="rounded-xl bg-white p-6 text-slate-600 shadow">
        No modules assigned to you yet.
    </div>
@endif

@endsection
