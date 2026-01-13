@extends('layouts.student')

@section('content')

@php
    $isOldStudent = auth()->user()->role->role === 'old_student';
@endphp

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
    <div class="rounded-3xl
        bg-gradient-to-r from-[#E6F400] via-[#9CD400] to-[#3FA34D]
        p-10 shadow-lg text-white flex items-center justify-between">

        <div>
            <h1 class="text-4xl font-extrabold">
                Student Dashboard 🎓
            </h1>

            <p class="mt-2 text-white/90 text-lg">
                {{ $isOldStudent
                    ? 'Your completed modules and results'
                    : 'View your completed and active modules' }}
            </p>
        </div>

        <div class="hidden md:flex h-14 w-14 rounded-full bg-white/20
                    items-center justify-center text-2xl">
            📚
        </div>
    </div>
</div>


{{-- ================= QUICK STATS (STUDENT ONLY) ================= --}}
@if(!$isOldStudent)
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-14">

    <div class="bg-green-100 rounded-2xl p-6 shadow">
        <p class="text-sm text-green-700">Completed</p>
        <p class="text-4xl font-bold text-green-900 mt-2">
            {{ $completedModules->count() }}
        </p>
    </div>

    <div class="bg-blue-100 rounded-2xl p-6 shadow">
        <p class="text-sm text-blue-700">Active Enrolled</p>
        <p class="text-4xl font-bold text-blue-900 mt-2">
            {{ $enrolledModules->count() }}
        </p>
    </div>

    <div class="bg-purple-100 rounded-2xl p-6 shadow">
        <p class="text-sm text-purple-700">Available Slots</p>
        <p class="text-4xl font-bold text-purple-900 mt-2">
            {{ max(0, 4 - $enrolledModules->count()) }}
        </p>
    </div>

</div>
@endif


{{-- ================= COMPLETED MODULES ================= --}}
<div class="mb-12">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">
        Completed Modules
    </h2>

    @forelse($completedModules as $module)
        @php $pivot = $module->pivot; @endphp

        <div class="bg-white rounded-xl shadow p-6 mb-4 border-l-4 border-green-400">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="font-semibold text-lg">{{ $module->name }}</h3>

                    <p class="text-sm text-gray-500">
                        Enrolled on
                        {{ \Carbon\Carbon::parse($pivot->enrolled_at ?? $pivot->created_at)->format('d M Y') }}
                    </p>

                    <p class="text-sm text-gray-500">
                        Completed on
                        {{ \Carbon\Carbon::parse($pivot->completed_at)->format('d M Y') }}
                    </p>
                </div>

                <span class="px-4 py-1 rounded-full text-sm font-semibold
                    {{ $pivot->status === 'PASS'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-red-100 text-red-700' }}">
                    {{ $pivot->status }}
                </span>
            </div>
        </div>

    @empty
        <p class="text-gray-500">No completed modules yet.</p>
    @endforelse
</div>


{{-- ================= ACTIVE MODULES (STUDENT ONLY) ================= --}}
@if(!$isOldStudent)
<div class="mb-12">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">
        Active Modules
    </h2>

    @forelse($enrolledModules as $module)
        @php $pivot = $module->pivot; @endphp

        <div class="bg-white rounded-xl shadow p-6 mb-4 border-l-4 border-blue-400">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="font-semibold text-lg">{{ $module->name }}</h3>
                    <p class="text-sm text-gray-500">
                        Enrolled on
                        {{ \Carbon\Carbon::parse($pivot->enrolled_at ?? $pivot->created_at)->format('d M Y') }}
                    </p>
                </div>

                <span class="px-4 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">
                    ENROLLED
                </span>
            </div>
        </div>

    @empty
        <p class="text-gray-500">No active modules.</p>
    @endforelse
</div>
@endif

@endsection
