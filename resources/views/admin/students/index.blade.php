@extends('layouts.admin')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="mb-8 rounded-2xl bg-gradient-to-r from-green-200 to-green-300 p-8">
    <h1 class="text-2xl font-bold text-green-900">🎓 Manage Students</h1>
    <p class="text-green-800">
        View students, enrolments, and update their roles
    </p>
</div>

{{-- ================= FLASH ================= --}}
@if(session('success'))
    <div class="mb-6 rounded-lg bg-green-100 px-4 py-3 text-green-800">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 rounded-lg bg-red-100 px-4 py-3 text-red-800">
        {{ session('error') }}
    </div>
@endif

{{-- ================= SEARCH ================= --}}
<form method="GET" class="mb-6 flex max-w-md gap-2">
    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Search student by name..."
        class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm
               focus:border-green-500 focus:ring-green-500"
    >
    <button
        class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white
               hover:bg-green-700">
        Search
    </button>
</form>

{{-- ================= TABLE ================= --}}
<div class="rounded-xl bg-white shadow">
    <table class="w-full text-sm">
        <thead class="bg-gray-100 text-gray-600">
            <tr>
                <th class="p-4 text-left">Student</th>
                <th class="p-4 text-left">Role</th>
                <th class="p-4 text-left">Results</th>
                <th class="p-4 text-left">Enrolments</th>
                <th class="p-4 text-left">Actions</th>
            </tr>
        </thead>

        <tbody class="divide-y">
        @forelse($students as $student)

            @php
                $passed  = $student->modules->where('pivot.status', 'PASS')->count();
                $failed  = $student->modules->where('pivot.status', 'FAIL')->count();
                $pending = $student->modules->whereNull('pivot.status')->count();
                $total   = $student->modules->count();
            @endphp

            <tr class="hover:bg-gray-50">

                {{-- STUDENT --}}
                <td class="p-4">
                    <div class="font-semibold text-gray-900">
                        {{ $student->name }}
                    </div>
                    <div class="text-gray-500 text-xs">
                        {{ $student->email }}
                    </div>
                </td>

                {{-- ROLE --}}
                <td class="p-4">
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                        {{ $student->role->role === 'old_student'
                            ? 'bg-purple-100 text-purple-700'
                            : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst(str_replace('_',' ', $student->role->role)) }}
                    </span>
                </td>

                {{-- RESULTS --}}
                <td class="p-4 space-x-1">
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

                    @if($pending)
                        <span class="rounded bg-yellow-100 px-2 py-1 text-xs text-yellow-700">
                            {{ $pending }} Pending
                        </span>
                    @endif

                    @if($total === 0)
                        <span class="text-gray-400 text-xs">No modules</span>
                    @endif
                </td>

                {{-- ENROLMENTS --}}
                <td class="p-4 text-gray-600">
                    {{ $total }} total
                </td>

                {{-- ACTIONS --}}
                <td class="p-4 space-y-2">

                    {{-- UPDATE ROLE --}}
                    <form method="POST"
                          action="{{ route('admin.students.updateRole', $student) }}"
                          class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')

                        <select name="role_id"
                                class="rounded-lg border-gray-300 px-2 py-1 text-sm
                                       focus:border-green-500 focus:ring-green-500">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ $student->user_role_id === $role->id ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_',' ', $role->role)) }}
                                </option>
                            @endforeach
                        </select>

                        <button
                            type="submit"
                            class="rounded-lg bg-green-600 px-3 py-1 text-sm text-white
                                   hover:bg-green-700">
                            Update
                        </button>
                    </form>

                    {{-- VIEW ENROLMENTS --}}
                    <a href="{{ route('admin.students.enrolments', $student) }}"
                       class="inline-block rounded-lg bg-gray-200 px-3 py-1 text-sm
                              text-gray-800 hover:bg-gray-300">
                        View Enrolments
                    </a>
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="5" class="p-6 text-center text-gray-500">
                    No students found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection
