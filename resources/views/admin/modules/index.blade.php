@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Manage Modules</h1>
    <p class="text-gray-500">Create modules, edit details, delete, and toggle availability.</p>
</div>

@if(session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
        {{ session('success') }}
    </div>
@endif

<!-- Add Module -->
<div class="bg-white rounded-xl shadow p-6 mb-8">
    <h2 class="text-lg font-semibold mb-4">Add New Module</h2>

    <form method="POST" action="{{ route('admin.modules.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Module Name</label>
            <input name="name" value="{{ old('name') }}"
                   class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                   placeholder="e.g. Web Development">
            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="3"
                      class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Optional...">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button class="inline-flex items-center rounded-lg bg-black px-4 py-2 text-white hover:bg-gray-800">
            Create Module
        </button>
    </form>
</div>

<!-- All Modules -->
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
                            <div class="font-medium text-gray-900">{{ $module->name }}</div>
                            @if($module->description)
                                <div class="text-gray-500">{{ $module->description }}</div>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            {{ $module->teachers_count }}
                        </td>

                        <td class="px-4 py-3">
                            @if($module->is_active)
                                <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-green-700">Active</span>
                            @else
                                <span class="inline-flex rounded-full bg-gray-200 px-3 py-1 text-gray-700">Archived</span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
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

                                <form method="POST" action="{{ route('admin.modules.destroy', $module) }}"
                                      onsubmit="return confirm('Delete this module? This will detach teachers/students too.')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-lg border border-red-200 px-3 py-1 text-red-700 hover:bg-red-50">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-6 text-center text-gray-500" colspan="4">
                            No modules found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
