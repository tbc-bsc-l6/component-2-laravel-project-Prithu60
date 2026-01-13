@extends('layouts.student')

@section('title', 'Modules')

@section('content')

{{-- FLASH --}}
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

{{-- HEADER --}}
<div class="mb-10 rounded-3xl bg-gradient-to-r from-[#4F46E5] to-[#22C55E]
            p-10 text-white shadow-lg">
    <h1 class="text-3xl font-extrabold">Modules 📚</h1>
    <p class="mt-2 text-white/90">
        Enroll in available modules (max 4 active)
    </p>
</div>

{{-- MODULE LIST --}}
<div class="space-y-5">

@foreach($modules as $module)

    <div class="bg-white rounded-2xl shadow p-6 flex justify-between items-center">

        <div>
            <h3 class="text-lg font-semibold">{{ $module->name }}</h3>
            <p class="text-sm text-gray-500">
                Students enrolled: {{ $module->students_count }} / {{ \App\Models\Module::MAX_STUDENTS }}
            </p>
        </div>

        {{-- STATUS / ACTION --}}
        <div>

            @if(in_array($module->id, $completedModules))
                <span class="px-4 py-2 rounded-full bg-green-100 text-green-700 text-sm">
                    COMPLETED
                </span>

            @elseif(in_array($module->id, $enrolledModules))
                <span class="px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-sm">
                    ENROLLED
                </span>

            @elseif(!$module->is_active)
                <span class="text-gray-500 text-sm">
                    UNAVAILABLE
                </span>

            @elseif($module->students_count >= \App\Models\Module::MAX_STUDENTS)
                <span class="text-red-600 text-sm">
                    FULL
                </span>

            @elseif($activeCount >= 4)
                <span class="text-gray-500 text-sm">
                    MAX REACHED
                </span>

            @else
                <form method="POST" action="{{ route('student.modules.enroll', $module) }}">
                    @csrf
                    <button class="px-5 py-2 bg-green-600 text-white rounded-xl
                                   hover:bg-green-700 transition">
                        Enroll
                    </button>
                </form>
            @endif

        </div>

    </div>

@endforeach

</div>

@endsection
