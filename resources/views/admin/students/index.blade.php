@extends('layouts.admin')

@section('title', 'Manage Students')
@section('header', 'Students')

@section('content')

{{-- =====================
| PAGE HEADER (GRADIENT)
===================== --}}
<div class="mb-10">
    <div class="rounded-3xl bg-gradient-to-r
                from-[#E6F400] via-[#9CD400] to-[#3FA34D]
                p-10 shadow-lg text-white">

        <h1 class="text-3xl font-bold">
            🎓 Manage Students
        </h1>

        <p class="mt-2 text-white/90">
            View students and update their roles
        </p>
    </div>
</div>

{{-- =====================
| FLASH MESSAGE
===================== --}}
@if(session('success'))
    <div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-5 py-4 text-green-800 shadow-sm">
        {{ session('success') }}
    </div>
@endif

{{-- =====================
| STUDENT TABLE CARD
===================== --}}
<div class="bg-white rounded-3xl shadow-lg p-8">

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="p-4 text-left">Name</th>
                    <th class="p-4 text-left">Email</th>
                    <th class="p-4 text-left">Current Role</th>
                    <th class="p-4 text-left">Change Role</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($students as $student)
                    <tr class="hover:bg-slate-50">

                        {{-- NAME --}}
                        <td class="p-4 font-medium text-slate-900">
                            {{ $student->name }}
                        </td>

                        {{-- EMAIL --}}
                        <td class="p-4 text-slate-600">
                            {{ $student->email }}
                        </td>

                        {{-- CURRENT ROLE --}}
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $student->role->role === 'student'
                                    ? 'bg-blue-100 text-blue-700'
                                    : 'bg-purple-100 text-purple-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $student->role->role)) }}
                            </span>
                        </td>

                        {{-- CHANGE ROLE --}}
                        <td class="p-4">
                            <form method="POST"
                                  action="{{ route('admin.students.updateRole', $student) }}"
                                  class="flex items-center gap-3">
                                @csrf
                                @method('PATCH')

                                <select name="role"
                                        class="rounded-xl border-gray-300 text-sm px-4 py-2
                                               focus:ring-green-500 focus:border-green-500">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ $student->role_id === $role->id ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $role->role)) }}
                                        </option>
                                    @endforeach
                                </select>

                                <button
                                    class="rounded-xl bg-gradient-to-r
                                           from-[#9CD400] to-[#3FA34D]
                                           px-5 py-2 text-white text-sm font-semibold
                                           shadow hover:opacity-90 transition">
                                    Update
                                </button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-slate-500">
                            No students found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
