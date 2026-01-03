@extends('layouts.admin')

@section('content')

<!-- ================= TOP INFO BANNER ================= -->
<div class="mb-8">
    <div class="flex items-center justify-between
                px-7 py-8 rounded-3xl shadow-sm
                bg-slate-800 text-white">

        <div>
            <h1 class="text-2xl font-bold">
                Manage Modules
            </h1>
            <p class="mt-1 text-sm text-white/80 max-w-xl">
                Create modules, assign teachers, manage students,
                and control module availability.
            </p>
        </div>

        <div class="hidden md:flex items-center justify-center
                    w-12 h-12 rounded-full bg-white/10 text-xl">
            📘
        </div>
    </div>
</div>

{{-- ================= SUCCESS MESSAGE ================= --}}
@if(session('success'))
    <div class="mb-5 rounded-xl bg-green-100 px-5 py-3 text-green-800 shadow-sm">
        {{ session('success') }}
    </div>
@endif

{{-- ================= SEARCH (SMALLER) ================= --}}
<form method="GET" action="{{ route('admin.modules.index') }}" class="mb-6">
    <div class="flex items-center gap-3 max-w-sm">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search module..."
            class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm
                   focus:ring-gray-300"
        >
        <button
            class="rounded-lg bg-gray-900 px-4 py-2 text-sm text-white
                   hover:bg-gray-800">
            Search
        </button>
    </div>
</form>

{{-- ================= ADD MODULE (SMALL & CUTE) ================= --}}
<div class="mb-8 max-w-xl rounded-2xl bg-white p-5 shadow">
    <h2 class="mb-3 text-base font-semibold">
        Add New Module
    </h2>

    <form method="POST" action="{{ route('admin.modules.store') }}" class="space-y-3">
        @csrf

        <div>
            <label class="block text-xs font-medium text-gray-600">
                Module Name
            </label>
            <input
                name="name"
                value="{{ old('name') }}"
                class="mt-1 w-full rounded-lg border-gray-300 px-3 py-2 text-sm
                       focus:ring-gray-300"
                placeholder="e.g. Web Development"
            >
            @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-600">
                Description
            </label>
            <textarea
                name="description"
                rows="2"
                class="mt-1 w-full rounded-lg border-gray-300 px-3 py-2 text-sm
                       focus:ring-gray-300"
                placeholder="Optional..."
            >{{ old('description') }}</textarea>
        </div>

        <button
            class="mt-2 rounded-lg bg-gray-900 px-4 py-2 text-sm text-white
                   hover:bg-gray-800">
            Create Module
        </button>
    </form>
</div>

{{-- ================= MODULE LIST ================= --}}
<div class="rounded-3xl bg-white p-6 shadow">
    <h2 class="mb-5 text-lg font-semibold">All Modules</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Name</th>
                    <th class="px-6 py-4 text-left font-semibold">Students</th>
                    <th class="px-6 py-4 text-left font-semibold">Teachers</th>
                    <th class="px-6 py-4 text-left font-semibold">Status</th>
                    <th class="px-6 py-4 text-left font-semibold">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">
            @forelse($modules as $module)
                <tr class="hover:bg-gray-50 transition">

                    {{-- NAME --}}
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">
                            {{ $module->name }}
                        </div>
                        @if($module->description)
                            <div class="text-xs text-gray-500">
                                {{ $module->description }}
                            </div>
                        @endif
                    </td>

                    {{-- STUDENTS --}}
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">
                            Active: {{ $module->active_students_count }} / 10
                        </div>
                        <div class="text-xs text-gray-500">
                            Completed: {{ $module->completed_students_count }}
                        </div>
                    </td>

                    {{-- TEACHERS --}}
                    <td class="px-6 py-4">
                        {{ $module->teachers_count }}
                    </td>

                    {{-- STATUS --}}
                    <td class="px-6 py-4">
                        @if($module->is_active)
                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs text-green-700">
                                Active
                            </span>
                        @else
                            <span class="rounded-full bg-gray-200 px-3 py-1 text-xs text-gray-700">
                                Archived
                            </span>
                        @endif
                    </td>

                    {{-- ACTIONS --}}
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-2">

                            <a href="{{ route('admin.modules.students', $module) }}"
                               class="rounded-lg border px-3 py-1 text-sm hover:bg-gray-100">
                                Students
                            </a>

                            <a href="{{ route('admin.modules.assign-teachers', $module) }}"
                               class="rounded-lg border px-3 py-1 text-sm hover:bg-gray-100">
                                Assign Teachers
                            </a>

                            <a href="{{ route('admin.modules.edit', $module) }}"
                               class="rounded-lg border px-3 py-1 text-sm hover:bg-gray-100">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('admin.modules.toggle', $module) }}">
                                @csrf
                                @method('PATCH')
                                <button class="rounded-lg border px-3 py-1 text-sm hover:bg-gray-100">
                                    {{ $module->is_active ? 'Archive' : 'Unarchive' }}
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                        No modules found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
