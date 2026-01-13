@extends('layouts.admin')

@section('content')

<!-- ================= COMPACT GREEN GRADIENT HEADER ================= -->
<div class="relative mb-6 overflow-hidden rounded-2xl shadow">
    <div class="relative h-24 md:h-28">

        <!-- Gradient -->
        <div class="absolute inset-0 bg-[linear-gradient(110deg,#00b84a_0%,#0b7a46_40%,#064a33_65%,#0a0a0a_100%)]"></div>

        <!-- Soft glow -->
        <div class="absolute -left-20 -bottom-20 h-[240px] w-[240px]
                    bg-[radial-gradient(circle_at_center,rgba(0,255,140,0.35)_0%,rgba(0,255,140,0)_60%)]
                    blur-3xl"></div>

        <!-- Content -->
        <div class="relative z-10 flex h-full items-center justify-between px-6">
            <div>
                <h1 class="text-lg md:text-xl font-bold text-white">
                    Assign Teachers
                </h1>
                <p class="mt-1 text-xs md:text-sm text-white/85">
                    {{ $module->name }}
                </p>
            </div>

            <div class="hidden md:flex items-center justify-center
                        rounded-full bg-white/15 px-3 py-1 text-xs text-white font-semibold">
                Module
            </div>
        </div>
    </div>
</div>

{{-- ================= BACK LINK ================= --}}
<div class="mb-4">
    <a href="{{ route('admin.modules.index') }}"
       class="text-sm text-emerald-700 hover:underline font-medium">
        ← Back to Modules
    </a>
</div>

{{-- ================= SUCCESS MESSAGE ================= --}}
@if(session('success'))
    <div class="mb-4 rounded-xl bg-emerald-100 px-4 py-3 text-emerald-900 shadow-sm border border-emerald-200 text-sm">
        {{ session('success') }}
    </div>
@endif

{{-- ================= ASSIGN TEACHERS (TEACHER-LIST STYLE) ================= --}}
<div class="max-w-3xl rounded-3xl bg-emerald-50/70 border border-emerald-100 p-6 shadow-sm">

    <div class="mb-4">
        <h2 class="text-base font-bold text-gray-900">
            Select Teachers
        </h2>
        <p class="text-sm text-gray-600">
            Choose one or more teachers to teach this module.
        </p>
    </div>

    <form method="POST"
          action="{{ route('admin.modules.assign-teachers.store', $module) }}">
        @csrf

        <div class="rounded-2xl bg-white border border-gray-100 shadow-sm divide-y">

            @forelse($teachers as $teacher)
                <label class="flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition cursor-pointer">

                    <div>
                        <p class="font-semibold text-gray-900">
                            {{ $teacher->name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $teacher->email }}
                        </p>
                    </div>

                    <input
                        type="checkbox"
                        name="teachers[]"
                        value="{{ $teacher->id }}"
                        class="h-5 w-5 rounded-md border-gray-300 text-emerald-600
                               focus:ring-emerald-500"
                        {{ $module->teachers->contains($teacher->id) ? 'checked' : '' }}
                    >
                </label>
            @empty
                <div class="px-6 py-10 text-center text-gray-500 text-sm">
                    No teachers available.
                </div>
            @endforelse

        </div>

        {{-- ACTION --}}
        <div class="mt-5 flex justify-end">
            <button
                type="submit"
                class="rounded-xl bg-emerald-700 px-5 py-2 text-sm font-semibold text-white
                       hover:bg-emerald-800 transition">
                Save Assignments
            </button>
        </div>

    </form>
</div>

@endsection
