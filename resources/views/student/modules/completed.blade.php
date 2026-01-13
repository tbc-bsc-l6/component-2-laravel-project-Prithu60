@extends('layouts.student')

@section('title', 'Completed Modules')
@section('header', 'Completed Modules')

@section('content')

<!-- ================= HERO ================= -->
<div class="mb-10">
    <div class="flex items-center justify-between
                px-8 py-10 rounded-2xl shadow-lg
                bg-gradient-to-r from-[#C7F000] via-[#8EE000] to-[#35B24A]
                text-white">

        <div>
            <h1 class="text-3xl font-bold">
                Completed Modules 🎓
            </h1>
            <p class="mt-2 text-white/90">
                View your completed modules and results
            </p>
        </div>

        <div class="hidden md:flex items-center justify-center
                    w-14 h-14 rounded-full bg-white/20 text-2xl">
            ✅
        </div>
    </div>
</div>

<!-- ================= LIST ================= -->
@if($completedModules->count())
    <div class="space-y-6">

        @foreach($completedModules as $module)
            <div class="bg-white rounded-2xl shadow border-l-4 border-green-400 p-6">

                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">
                            {{ $module->name }}
                        </h3>

                        <p class="mt-1 text-sm text-slate-600">
                            Enrolled on:
                            {{ \Carbon\Carbon::parse($module->pivot->enrolled_at)->format('d M Y') }}
                        </p>

                        <p class="text-sm text-slate-600">
                            Completed on:
                            {{ \Carbon\Carbon::parse($module->pivot->completed_at)->format('d M Y') }}
                        </p>
                    </div>

                    <div>
                        @if($module->pivot->status === 'PASS')
                            <span class="px-4 py-2 rounded-full text-sm
                                         bg-green-100 text-green-700 font-semibold">
                                PASS
                            </span>
                        @else
                            <span class="px-4 py-2 rounded-full text-sm
                                         bg-red-100 text-red-700 font-semibold">
                                FAIL
                            </span>
                        @endif
                    </div>
                </div>

            </div>
        @endforeach

    </div>
@else
    <div class="bg-white p-6 rounded-xl shadow text-slate-600">
        You have not completed any modules yet.
    </div>
@endif

@endsection
