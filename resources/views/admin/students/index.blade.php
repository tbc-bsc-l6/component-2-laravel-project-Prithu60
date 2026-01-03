@extends('layouts.admin')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="mb-10 rounded-3xl p-8 shadow-sm"
     style="background-color:#8FB37A;">
    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
        🎓 Manage Students
    </h1>
    <p class="mt-2 text-gray-800">
        View students, enrolments, and update their roles
    </p>
</div>

{{-- ================= FLASH ================= --}}
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

{{-- ================= SEARCH ================= --}}
<form method="GET" class="mb-8 flex max-w-lg items-center gap-3">
    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Search student by name..."
        class="flex-1 rounded-xl border border-gray-300 px-4 py-2 text-sm
               focus:outline-none focus:ring-2 focus:ring-gray-300"
    >
    <button
        class="rounded-xl bg-gray-800 px-5 py-2 text-sm font-semibold text-white
               hover:bg-gray-900 transition">
        Search
    </button>
</form>

{{-- ================= TABLE CARD ================= --}}
<div class="rounded-3xl bg-gray-100 p-6 shadow-lg">

    <div class="overflow-hidden rounded-2xl bg-white">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="px-6 py-4 text-left">Student</th>
                    <th class="px-6 py-4 text-left">Role</th>
                    <th class="px-6 py-4 text-left">Results</th>
                    <th class="px-6 py-4 text-left">Enrolments</th>
                    <th class="px-6 py-4 text-left">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">
            @forelse($students as $student)

                @php
                    $active     = $student->modules->whereNull('pivot.completed_at')->count();
                    $completed  = $student->modules->whereNotNull('pivot.completed_at')->count();
                    $passed     = $student->modules->where('pivot.status', 'PASS')->count();
                    $failed     = $student->modules->where('pivot.status', 'FAIL')->count();
                @endphp

                {{-- 🔴 NO GREEN HOVER ANYMORE --}}
                <tr class="hover:bg-gray-50 transition">

                    {{-- STUDENT --}}
                    <td class="px-6 py-5">
                        <div class="font-semibold text-gray-900">
                            {{ $student->name }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $student->email }}
                        </div>
                    </td>

                    {{-- ROLE --}}
                    <td class="px-6 py-5">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                            {{ $student->role->role === 'old_student'
                                ? 'bg-purple-100 text-purple-700'
                                : 'bg-blue-100 text-blue-700' }}">
                            {{ ucfirst(str_replace('_',' ', $student->role->role)) }}
                        </span>
                    </td>

                    {{-- RESULTS --}}
                    <td class="px-6 py-5 space-x-1">
                        @if($passed)
                            <span class="rounded bg-green-100 px-2 py-1 text-xs text-green-700">
                                {{ $passed }} PASS
                            </span>
                        @endif

                        @if($failed)
                            <span class="rounded bg-red-100 px-2 py-1 text-xs text-red-700">
                                {{ $failed }} FAIL
                            </span>
                        @endif

                        @if(!$passed && !$failed)
                            <span class="text-gray-400 text-xs">No results</span>
                        @endif
                    </td>

                    {{-- ENROLMENTS --}}
                    <td class="px-6 py-5 text-sm text-gray-700">
                        <div class="font-semibold">
                            Active: {{ $active }}
                        </div>
                        <div class="text-xs text-gray-500">
                            Completed: {{ $completed }}
                        </div>
                    </td>

                    {{-- ACTIONS --}}
                    <td class="px-6 py-5 space-y-2">

                        <form method="POST"
                              action="{{ route('admin.students.updateRole', $student) }}"
                              class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')

                            <select name="role_id"
                                    class="rounded-lg border-gray-300 px-2 py-1 text-sm
                                           focus:ring-gray-300">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ $student->user_role_id === $role->id ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_',' ', $role->role)) }}
                                    </option>
                                @endforeach
                            </select>

                            <button
                                type="submit"
                                class="rounded-lg bg-gray-800 px-3 py-1 text-sm text-white
                                       hover:bg-gray-900">
                                Update
                            </button>
                        </form>

                        <a href="{{ route('admin.students.enrolments', $student) }}"
                           class="inline-block rounded-lg bg-gray-200 px-3 py-1 text-sm
                                  text-gray-800 hover:bg-gray-300">
                            View Enrolments
                        </a>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                        No students found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
