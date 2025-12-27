@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        Assign Teachers — {{ $module->name }}
    </h1>
    <p class="text-gray-500">
        Select one or more teachers to teach this module.
    </p>
</div>

<div class="mb-4">
    <a href="{{ route('admin.modules.index') }}"
       class="text-sm text-indigo-600 underline">
        ← Back to Modules
    </a>
</div>

@if(session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow p-6 max-w-xl">
    <form method="POST"
          action="{{ route('admin.modules.assign-teachers.store', $module) }}">
        @csrf

        <h2 class="text-lg font-semibold mb-4">
            Teachers
        </h2>

        <div class="space-y-3">
            @forelse($teachers as $teacher)
                <label class="flex items-center gap-3">
                    <input
                        type="checkbox"
                        name="teachers[]"
                        value="{{ $teacher->id }}"
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        {{ $module->teachers->contains($teacher->id) ? 'checked' : '' }}
                    >
                    <span>
                        <span class="font-medium text-gray-800">
                            {{ $teacher->name }}
                        </span>
                        <span class="text-sm text-gray-500">
                            ({{ $teacher->email }})
                        </span>
                    </span>
                </label>
            @empty
                <p class="text-gray-500">
                    No teachers available.
                </p>
            @endforelse
        </div>

        <div class="mt-6">
            <button
                type="submit"
                class="inline-flex items-center rounded-lg bg-black px-4 py-2 text-white hover:bg-gray-800">
                Save Assignments
            </button>
        </div>
    </form>
</div>
@endsection
