@extends('layouts.admin')

@section('content')

{{-- PAGE HEADER --}}
<div class="mb-6">
    <div class="rounded-2xl p-5 shadow-md
                bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600
                text-white flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold tracking-tight flex items-center gap-2">
                👩‍🏫 Manage Teachers
            </h1>
            <p class="text-white/80 text-sm mt-1">
                Create teachers and optionally assign modules
            </p>
        </div>

        <div class="hidden sm:flex gap-2">
            <span class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center">🎓</span>
            <span class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center">📚</span>
        </div>
    </div>
</div>

{{-- FLASH --}}
@if(session('success'))
    <div class="mb-4 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-green-800">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-red-800">
        <ul class="list-disc pl-5 space-y-1">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- CREATE TEACHER --}}
<div class="bg-white rounded-2xl shadow-sm border border-indigo-100
            p-5 mb-10 max-w-2xl">

    <h2 class="text-lg font-semibold text-gray-900 mb-1">
        Add New Teacher
    </h2>
    <p class="text-sm text-gray-500 mb-5">
        Fill teacher details. Module assignment is optional.
    </p>

    <form method="POST" action="{{ route('admin.teachers.store') }}" class="space-y-4">
        @csrf

        {{-- NAME --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-700">First Name</label>
                <input name="first_name" value="{{ old('first_name') }}"
                       class="mt-1 w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Prithu">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Last Name</label>
                <input name="last_name" value="{{ old('last_name') }}"
                       class="mt-1 w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Adhikari">
            </div>
        </div>

        {{-- EMAIL --}}
        <div>
            <label class="text-sm font-medium text-gray-700">Email</label>
            <input name="email" value="{{ old('email') }}"
                   class="mt-1 w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                   placeholder="teacher@college.com">
        </div>

        {{-- PASSWORD --}}
        <div>
            <label class="text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password"
                   class="mt-1 w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                   placeholder="••••••••">
            <p class="text-xs text-gray-400 mt-1">Minimum 6 characters</p>
        </div>

        {{-- ASSIGN MODULES DROPDOWN --}}
        <details class="rounded-xl border border-gray-200 bg-gradient-to-b from-gray-50 to-white p-3">
            <summary class="cursor-pointer font-medium text-indigo-700 flex items-center justify-between">
                <span>Assign Modules (optional)</span>
                <span class="text-sm">▼</span>
            </summary>

            <div class="mt-4 space-y-3">
                @forelse($modules as $module)
                    <label class="flex items-start gap-3 rounded-lg bg-white border p-3 hover:border-indigo-300 transition">
                        <input type="checkbox"
                               name="modules[]"
                               value="{{ $module->id }}"
                               class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                               {{ in_array($module->id, old('modules', [])) ? 'checked' : '' }}>

                        <div>
                            <div class="font-medium text-gray-900">
                                {{ $module->name }}
                            </div>
                            @if($module->description)
                                <div class="text-sm text-gray-500">
                                    {{ $module->description }}
                                </div>
                            @endif
                        </div>
                    </label>
                @empty
                    <p class="text-gray-500 text-sm">No modules available.</p>
                @endforelse
            </div>
        </details>

        {{-- SUBMIT --}}
        <div class="pt-3">
            <button
                class="w-full sm:w-auto rounded-xl
                       bg-gradient-to-r from-indigo-600 to-purple-600
                       px-6 py-3 text-white font-semibold shadow hover:opacity-90">
                Create Teacher
            </button>
        </div>
    </form>
</div>

{{-- TEACHER LIST --}}
<div class="bg-white rounded-2xl shadow p-6">
    <h2 class="text-lg font-semibold mb-4">Teacher List</h2>

    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="p-3 text-left">Name</th>
                <th class="p-3 text-left">Email</th>
                <th class="p-3 text-left">Modules</th>
                <th class="p-3 text-left">Action</th>
            </tr>
        </thead>

        <tbody class="divide-y">
            @forelse($teachers as $teacher)
                <tr>
                    <td class="p-3 font-medium">{{ $teacher->name }}</td>
                    <td class="p-3">{{ $teacher->email }}</td>
                    <td class="p-3">
                        @if($teacher->teachingModules->isEmpty())
                            <span class="text-gray-400">—</span>
                        @else
                            <div class="flex flex-wrap gap-2">
                                @foreach($teacher->teachingModules as $m)
                                    <span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs">
                                        {{ $m->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td class="p-3">
                        <form method="POST"
                              action="{{ route('admin.teachers.destroy', $teacher) }}"
                              onsubmit="return confirm('Delete this teacher?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-6 text-center text-gray-500">
                        No teachers found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
