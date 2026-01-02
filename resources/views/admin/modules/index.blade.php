@extends('layouts.admin')

@section('content')

<!-- ================= TOP INFO BANNER ================= -->
<div class="mb-8">
    <div class="flex items-center justify-between
                px-8 py-10 rounded-2xl shadow-lg
                bg-gradient-to-r from-slate-700 via-slate-800 to-slate-900
                text-white">

        <div>
            <h1 class="text-3xl font-bold">
                Manage Modules
            </h1>
            <p class="mt-2 text-white/80 max-w-2xl">
                Create modules, edit details, assign teachers, manage students,
                and control module availability.
            </p>
        </div>

        <div class="hidden md:flex items-center justify-center
                    w-14 h-14 rounded-full bg-white/10 text-2xl">
            📘
        </div>
    </div>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
        {{ session('success') }}
    </div>
@endif

{{-- Add Module --}}
<div class="bg-white rounded-xl shadow p-6 mb-8">
    <h2 class="text-lg font-semibold mb-4">Add New Module</h2>

    <form method="POST" action="{{ route('admin.modules.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Module Name</label>
            <input
                name="name"
                value="{{ old('name') }}"
                class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="e.g. Web Development"
            >
            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea
                name="description"
                rows="3"
                class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Optional..."
            >{{ old('description') }}</textarea>
            @error('description')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button class="inline-flex items-center rounded-lg bg-black px-4 py-2 text-white hover:bg-gray-800">
            Create Module
        </button>
    </form>
</div>

{{-- All Modules --}}
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-lg font-semibold mb-4">All Modules</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Name</th>
                    <th class="px-4 py-3 text-left font-semibold">Teachers</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-left font-semibold">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($modules as $module)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">
                                {{ $module->name }}
                            </div>
                            @if($module->description)
                                <div class="text-gray-500">
                                    {{ $module->description }}
                                </div>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            {{ $module->teachers_count }}
                        </td>

                        <td class="px-4 py-3">
                            @if($module->is_active)
                                <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-green-700">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-gray-200 px-3 py-1 text-gray-700">
                                    Archived
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">

                                <a href="{{ route('admin.modules.students', $module) }}"
                                   class="rounded-lg border px-3 py-1 hover:bg-gray-50">
                                    Students
                                </a>

                                <a href="{{ route('admin.modules.assign-teachers', $module) }}"
                                   class="rounded-lg border px-3 py-1 hover:bg-gray-50">
                                    Assign Teachers
                                </a>

                                <a href="{{ route('admin.modules.edit', $module) }}"
                                   class="rounded-lg border px-3 py-1 hover:bg-gray-50">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('admin.modules.toggle', $module) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="rounded-lg border px-3 py-1 hover:bg-gray-50">
                                        {{ $module->is_active ? 'Archive' : 'Unarchive' }}
                                    </button>
                                </form>

                                <form
                                    method="POST"
                                    action="{{ route('admin.modules.destroy', $module) }}"
                                    onsubmit="return confirm('Delete this module? This will detach teachers and students.')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="rounded-lg border border-red-200 px-3 py-1 text-red-700 hover:bg-red-50">
                                        Delete
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                            No modules found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
