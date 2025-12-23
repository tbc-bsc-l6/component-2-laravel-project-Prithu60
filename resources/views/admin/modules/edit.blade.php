@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Edit Module</h1>
    <p class="text-gray-500">Update module details.</p>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <form method="POST" action="{{ route('admin.modules.update', $module) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700">Module Name</label>
            <input name="name" value="{{ old('name', $module->name) }}"
                   class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="3"
                      class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $module->description) }}</textarea>
            @error('description')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-2">
            <button class="rounded-lg bg-black px-4 py-2 text-white hover:bg-gray-800">
                Save Changes
            </button>

            <a href="{{ route('admin.modules.index') }}"
               class="rounded-lg border px-4 py-2 hover:bg-gray-50">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
