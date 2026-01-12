@extends('layouts.admin')

@section('content')

<!-- ================= COMPACT GRADIENT HEADER (MATCH MODULES) ================= -->
<div class="relative mb-6 overflow-hidden rounded-2xl shadow">
    <div class="relative h-28 md:h-32">

        <!-- Gradient -->
        <div class="absolute inset-0 bg-[linear-gradient(110deg,#00b84a_0%,#0b7a46_35%,#064a33_60%,#0a0a0a_100%)]"></div>

        <!-- Soft glow -->
        <div class="absolute -left-24 -bottom-24 h-[280px] w-[280px]
                    bg-[radial-gradient(circle_at_center,rgba(0,255,140,0.45)_0%,rgba(0,255,140,0)_60%)]
                    blur-3xl"></div>

        <div class="absolute left-16 top-4 h-[220px] w-[220px]
                    bg-[radial-gradient(circle_at_center,rgba(0,200,90,0.22)_0%,rgba(0,200,90,0)_60%)]
                    blur-3xl"></div>

        <!-- Content -->
        <div class="relative z-10 flex h-full items-center justify-between px-6 md:px-8">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-white leading-tight">
                    Manage Students
                </h1>
                <p class="mt-1 text-xs md:text-sm text-white/85 max-w-2xl">
                    View students, enrolments, and update their roles
                </p>
            </div>

            <div class="hidden md:flex items-center justify-center
                        w-9 h-9 rounded-full bg-white/15 text-white text-base">
                🎓
            </div>
        </div>
    </div>
</div>

{{-- ================= FLASH ================= --}}
@if(session('success'))
    <div class="mb-6 rounded-xl bg-emerald-100 px-6 py-4 text-emerald-900 shadow-sm border border-emerald-200 text-sm">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 rounded-xl bg-red-100 px-6 py-4 text-red-800 shadow-sm border border-red-200 text-sm">
        {{ session('error') }}
    </div>
@endif

{{-- ================= INSTANT SEARCH (MATCH MODULES STYLE) ================= --}}
<div class="mb-4 max-w-sm">
    <input
        id="studentSearch"
        type="text"
        placeholder="Search student by name or email..."
        class="w-full rounded-xl border-gray-300 px-4 py-2 text-sm
               focus:border-emerald-500 focus:ring-emerald-500">
</div>

{{-- ================= TABLE CARD ================= --}}
<div class="rounded-3xl bg-emerald-50/70 border border-emerald-100 p-6 shadow-sm">

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-gray-100">
        <table class="w-full text-sm" id="studentsTable">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Student</th>
                    <th class="px-6 py-4 text-left font-semibold">Role</th>
                    <th class="px-6 py-4 text-left font-semibold">Results</th>
                    <th class="px-6 py-4 text-left font-semibold">Enrolments</th>
                    <th class="px-6 py-4 text-left font-semibold">Actions</th>
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

                <tr class="hover:bg-gray-50 transition">

                    {{-- STUDENT --}}
                    <td class="px-6 py-5">
                        <div class="font-semibold text-gray-900 student-name">
                            {{ $student->name }}
                        </div>
                        <div class="text-xs text-gray-500 student-email">
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
                            <span class="rounded bg-emerald-100 px-2 py-1 text-xs text-emerald-700">
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
                                           focus:ring-emerald-500">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ $student->user_role_id === $role->id ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_',' ', $role->role)) }}
                                    </option>
                                @endforeach
                            </select>

                            <button
                                type="submit"
                                class="rounded-lg bg-gray-900 px-3 py-1 text-sm text-white
                                       hover:bg-black transition">
                                Update
                            </button>
                        </form>

                        <a href="{{ route('admin.students.enrolments', $student) }}"
                           class="inline-block rounded-lg bg-gray-200 px-3 py-1 text-sm
                                  text-gray-800 hover:bg-gray-300 transition">
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

{{-- ================= SEARCH SCRIPT ================= --}}
<script>
document.getElementById('studentSearch').addEventListener('keyup', function () {
    const value = this.value.toLowerCase();

    document.querySelectorAll('#studentsTable tbody tr').forEach(row => {
        const name  = row.querySelector('.student-name').innerText.toLowerCase();
        const email = row.querySelector('.student-email').innerText.toLowerCase();

        row.style.display = (name.includes(value) || email.includes(value)) ? '' : 'none';
    });
});
</script>

@endsection
