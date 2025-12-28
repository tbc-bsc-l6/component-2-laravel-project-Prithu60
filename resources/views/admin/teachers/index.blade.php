@extends('layouts.admin')

@section('content')

{{-- =========================
| PAGE HEADER (LIGHT PURPLE GRADIENT)
========================= --}}
<div class="mb-12">
    <div class="rounded-3xl bg-gradient-to-r
                from-[#F2E9FF] via-[#EAD8FF] to-[#EFD6F5]
                p-12 shadow-md">
        <h1 class="text-4xl font-extrabold text-gray-900">
            🎓 Manage Teachers
        </h1>
        <p class="text-gray-700 mt-3 text-lg">
            Create teachers, assign modules, and manage teaching responsibilities
        </p>
    </div>
</div>

{{-- =========================
| FLASH MESSAGES
========================= --}}
@if(session('success'))
    <div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-5 py-4 text-green-800 shadow-sm">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-5 py-4 text-red-800 shadow-sm">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- =========================
| ADD TEACHER CARD
========================= --}}
<div class="bg-white rounded-3xl shadow-lg p-10 mb-14 max-w-4xl">

    <h2 class="text-2xl font-semibold text-gray-900 mb-2">
        Add New Teacher
    </h2>
    <p class="text-sm text-gray-500 mb-10">
        Enter teacher details below. Module assignment is optional.
    </p>

    <form method="POST" action="{{ route('admin.teachers.store') }}" class="space-y-7">
        @csrf

        {{-- NAME --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="text-sm font-medium text-gray-700">First Name</label>
                <input
                    name="first_name"
                    value="{{ old('first_name') }}"
                    class="mt-2 w-full rounded-xl border-gray-300 px-5 py-3 focus:ring-purple-500 focus:border-purple-500"
                    placeholder="Prithu">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Last Name</label>
                <input
                    name="last_name"
                    value="{{ old('last_name') }}"
                    class="mt-2 w-full rounded-xl border-gray-300 px-5 py-3 focus:ring-purple-500 focus:border-purple-500"
                    placeholder="Adhikari">
            </div>
        </div>

        {{-- EMAIL --}}
        <div>
            <label class="text-sm font-medium text-gray-700">Email</label>
            <input
                name="email"
                value="{{ old('email') }}"
                class="mt-2 w-full rounded-xl border-gray-300 px-5 py-3 focus:ring-purple-500 focus:border-purple-500"
                placeholder="teacher@college.com">
        </div>

        {{-- PASSWORD --}}
        <div>
            <label class="text-sm font-medium text-gray-700">Password</label>
            <input
                type="password"
                name="password"
                class="mt-2 w-full rounded-xl border-gray-300 px-5 py-3 focus:ring-purple-500 focus:border-purple-500"
                placeholder="••••••••">
            <p class="text-xs text-gray-400 mt-1">Minimum 6 characters</p>
        </div>

        {{-- ASSIGN MODULES --}}
        <details class="rounded-2xl border border-purple-200 bg-purple-50 p-6">
            <summary class="cursor-pointer font-semibold text-gray-800 flex items-center justify-between">
                <span>Assign Modules (optional)</span>
                <span class="text-purple-600">▼</span>
            </summary>

            <div class="mt-6 space-y-4">
                @forelse($modules as $module)
                    <label class="flex items-start gap-4 rounded-xl bg-white border border-gray-200 p-4 hover:border-purple-400 transition">
                        <input
                            type="checkbox"
                            name="modules[]"
                            value="{{ $module->id }}"
                            class="mt-1 rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                            {{ in_array($module->id, old('modules', [])) ? 'checked' : '' }}>

                        <div>
                            <div class="font-medium text-gray-900">
                                {{ $module->name }}
                            </div>
                            @if($module->description)
                                <div class="text-sm text-gray-600 mt-1">
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
        <div class="pt-6">
            <button
                class="w-full md:w-auto rounded-xl bg-gradient-to-r
                       from-[#EAD8FF] to-[#D9C6FF]
                       px-10 py-3 text-gray-900 font-semibold shadow-md hover:opacity-90 transition">
                Create Teacher
            </button>
        </div>
    </form>
</div>

{{-- =========================
| TEACHER LIST (LIGHT PURPLE)
========================= --}}
<div class="bg-gradient-to-br from-[#F6F0FF] to-[#EEE6FF] rounded-3xl shadow-lg p-10">
    <h2 class="text-2xl font-semibold mb-6 text-gray-900">
        Teacher List
    </h2>

    <div class="overflow-x-auto">
        <table class="w-full text-sm bg-white rounded-2xl overflow-hidden">
            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="p-4 text-left">Name</th>
                    <th class="p-4 text-left">Email</th>
                    <th class="p-4 text-left">Modules</th>
                    <th class="p-4 text-left">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($teachers as $teacher)
                    <tr class="hover:bg-gray-50">
                        <td class="p-4 font-medium text-gray-900">
                            {{ $teacher->name }}
                        </td>

                        <td class="p-4 text-gray-700">
                            {{ $teacher->email }}
                        </td>

                        <td class="p-4">
                            @if($teacher->teachingModules->isEmpty())
                                <span class="text-gray-400">—</span>
                            @else
                                <div class="flex flex-wrap gap-2">
                                    @foreach($teacher->teachingModules as $m)
                                        <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs">
                                            {{ $m->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>

                        <td class="p-4">
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
</div>

@endsection
