@extends('layouts.student')

@section('content')

{{-- ================= FLASH MESSAGES ================= --}}
@if(session('success'))
    <div class="mb-6 rounded-xl bg-green-100 px-6 py-4 text-green-800 shadow">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 rounded-xl bg-red-100 px-6 py-4 text-red-800 shadow">
        {{ session('error') }}
    </div>
@endif


{{-- ================= HERO HEADER ================= --}}
<div class="mb-12">
    <div class="rounded-3xl bg-gradient-to-r
                from-[#E6F400] via-[#9CD400] to-[#3FA34D]
                p-10 shadow-lg text-white flex items-center justify-between">

        <div>
            <h1 class="text-4xl font-extrabold">
                Student Dashboard 🎓
            </h1>
            <p class="mt-2 text-white/90 text-lg">
                View all modules, availability and your enrolment status
            </p>
        </div>

        <div class="hidden md:flex h-14 w-14 rounded-full bg-white/20
                    items-center justify-center text-2xl">
            📚
        </div>
    </div>
</div>


{{-- ================= QUICK STATS ================= --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-14">

    <div class="bg-blue-100 rounded-2xl p-6 shadow">
        <p class="text-sm text-blue-700">Active Enrolled</p>
        <p class="text-4xl font-bold text-blue-900 mt-2">
            {{ $enrolledModules->count() }}
        </p>
    </div>

    <div class="bg-green-100 rounded-2xl p-6 shadow">
        <p class="text-sm text-green-700">Completed</p>
        <p class="text-4xl font-bold text-green-900 mt-2">
            {{ $completedModules->count() }}
        </p>
    </div>

    <div class="bg-purple-100 rounded-2xl p-6 shadow">
        <p class="text-sm text-purple-700">Available Slots</p>
        <p class="text-4xl font-bold text-purple-900 mt-2">
            {{ max(0, 4 - $enrolledModules->count()) }}
        </p>
    </div>

</div>


{{-- ================= ALL MODULES ================= --}}
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">
        All Modules
    </h2>
    <p class="text-gray-500">
        Availability, enrolment limits and your current status
    </p>
</div>

<div class="space-y-6">

@forelse($modules as $module)

    @php
        $isEnrolled  = $module->isEnrolledBy(auth()->user());
        $isCompleted = $module->isCompletedBy(auth()->user());
        $isFull      = $module->isFull();
    @endphp

    <div class="bg-white rounded-2xl shadow p-6 border-l-4
        {{ $isCompleted ? 'border-green-400'
            : ($isEnrolled ? 'border-blue-400'
            : ($isFull ? 'border-red-400'
            : ($module->available ? 'border-green-300' : 'border-gray-300'))) }}">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">

            {{-- MODULE INFO --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ $module->name }}
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Students enrolled:
                    {{ $module->enrolledStudentsCount() }}
                    / {{ \App\Models\Module::MAX_STUDENTS }}
                </p>

                @if($isCompleted)
                    <p class="text-sm mt-2 text-gray-600">
                        Completed on
                        {{ \Carbon\Carbon::parse($module->pivot->completed_at)->format('d M Y') }}
                    </p>
                @endif
            </div>

            {{-- STATUS + ACTION --}}
            <div class="flex items-center gap-4 flex-wrap">

                {{-- STATUS BADGE --}}
                @if($isCompleted)
                    <span class="px-4 py-1 rounded-full text-sm font-semibold
                        {{ $module->pivot->status === 'PASS'
                            ? 'bg-green-100 text-green-700'
                            : 'bg-red-100 text-red-700' }}">
                        {{ $module->pivot->status }}
                    </span>

                @elseif($isEnrolled)
                    <span class="px-4 py-1 rounded-full text-sm font-semibold
                                 bg-blue-100 text-blue-700">
                        ENROLLED
                    </span>

                @elseif(!$module->available)
                    <span class="px-4 py-1 rounded-full text-sm font-semibold
                                 bg-gray-100 text-gray-600">
                        UNAVAILABLE
                    </span>

                @elseif($isFull)
                    <span class="px-4 py-1 rounded-full text-sm font-semibold
                                 bg-red-100 text-red-700">
                        FULL
                    </span>

                @else
                    <span class="px-4 py-1 rounded-full text-sm font-semibold
                                 bg-green-100 text-green-700">
                        AVAILABLE
                    </span>
                @endif

                {{-- ENROLL BUTTON --}}
                @if(
                    !$isCompleted &&
                    !$isEnrolled &&
                    $module->available &&
                    !$isFull &&
                    $enrolledModules->count() < 4
                )
                    <form method="POST" action="{{ route('student.modules.enroll', $module) }}">
                        @csrf
                        <button
                            class="px-5 py-2 rounded-xl
                                   bg-gradient-to-r from-green-500 to-emerald-600
                                   text-white text-sm font-semibold
                                   hover:opacity-90 transition">
                            Enroll
                        </button>
                    </form>
                @endif

            </div>
        </div>
    </div>

@empty
    <div class="bg-white p-6 rounded-xl shadow text-gray-500">
        No modules found.
    </div>
@endforelse

</div>

@endsection
