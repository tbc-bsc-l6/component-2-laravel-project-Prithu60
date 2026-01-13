@extends('layouts.admin')

@section('content')

{{-- HEADER --}}
<div class="relative mb-6 overflow-hidden rounded-2xl shadow">
    <div class="relative h-28 md:h-32">
        <div class="absolute inset-0 bg-[linear-gradient(110deg,#00b84a_0%,#0b7a46_35%,#064a33_60%,#0a0a0a_100%)]"></div>
        <div class="relative z-10 flex h-full items-center justify-between px-6">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-white">Manage Students</h1>
                <p class="mt-1 text-sm text-white/80">View students, enrolments, and update roles</p>
            </div>
        </div>
    </div>
</div>

{{-- FLASH --}}
@if(session('success'))
    <div class="mb-4 rounded bg-emerald-100 p-3 text-emerald-800">
        {{ session('success') }}
    </div>
@endif

{{-- SEARCH --}}
<input id="studentSearch" type="text"
       placeholder="Search by name or email..."
       class="mb-4 w-full max-w-sm rounded border px-3 py-2 text-sm">

{{-- TABLE --}}
<div class="overflow-hidden rounded-2xl bg-white shadow border">
<table class="w-full text-sm" id="studentsTable">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-4 text-left">Student</th>
            <th class="px-6 py-4 text-left">Role</th>
            <th class="px-6 py-4 text-left">Results</th>
            <th class="px-6 py-4 text-left">Enrolments</th>
            <th class="px-6 py-4 text-left">Actions</th>
        </tr>
    </thead>

    <tbody class="divide-y">
    @foreach($students as $student)

        @php
            $active    = $student->modules->whereNull('pivot.completed_at')->count();
            $completed = $student->modules->whereNotNull('pivot.completed_at')->count();
            $passed    = $student->modules->where('pivot.status','PASS')->count();
            $failed    = $student->modules->where('pivot.status','FAIL')->count();
        @endphp

        <tr>
            {{-- STUDENT --}}
            <td class="px-6 py-4">
                <div class="font-semibold student-name">{{ $student->name }}</div>
                <div class="text-xs text-gray-500 student-email">{{ $student->email }}</div>
            </td>

            {{-- ROLE BADGE --}}
            <td class="px-6 py-4">
                <span class="rounded px-2 py-1 text-xs
                    {{ $student->role->role === 'old_student'
                        ? 'bg-purple-100 text-purple-700'
                        : ($student->role->role === 'teacher'
                            ? 'bg-indigo-100 text-indigo-700'
                            : 'bg-blue-100 text-blue-700') }}">
                    {{ ucfirst(str_replace('_',' ', $student->role->role)) }}
                </span>
            </td>

            {{-- RESULTS --}}
            <td class="px-6 py-4">
                @if($passed)
                    <span class="rounded bg-green-100 px-2 py-1 text-xs">{{ $passed }} PASS</span>
                @endif
                @if($failed)
                    <span class="rounded bg-red-100 px-2 py-1 text-xs">{{ $failed }} FAIL</span>
                @endif
                @if(!$passed && !$failed)
                    <span class="text-xs text-gray-400">No results</span>
                @endif
            </td>

            {{-- ENROLMENTS --}}
            <td class="px-6 py-4">
                <div>Active: {{ $active }}</div>
                <div class="text-xs text-gray-500">Completed: {{ $completed }}</div>
            </td>

            {{-- ACTIONS --}}
            <td class="px-6 py-4">
                <form method="POST"
                      action="{{ route('admin.students.updateRole', $student) }}"
                      onsubmit="return handleRoleChange(this, {{ $student->id }})"
                      class="flex items-center gap-2">
                    @csrf
                    @method('PATCH')

                    <select name="role_id"
                            class="rounded border px-2 py-1 text-sm role-select">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ $student->user_role_id === $role->id ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_',' ', $role->role)) }}
                            </option>
                        @endforeach
                        <option value="teacher">Teacher</option>
                    </select>

                    <button class="rounded bg-gray-900 px-3 py-1 text-sm text-white">
                        Update
                    </button>
                </form>

                <a href="{{ route('admin.students.enrolments', $student) }}"
                   class="mt-1 inline-block text-xs text-gray-600 underline">
                    View Enrolments
                </a>
            </td>
        </tr>

    @endforeach
    </tbody>
</table>
</div>

{{-- SEARCH --}}
<script>
document.getElementById('studentSearch').addEventListener('keyup', function () {
    const v = this.value.toLowerCase();
    document.querySelectorAll('#studentsTable tbody tr').forEach(r => {
        const n = r.querySelector('.student-name').innerText.toLowerCase();
        const e = r.querySelector('.student-email').innerText.toLowerCase();
        r.style.display = (n.includes(v) || e.includes(v)) ? '' : 'none';
    });
});

function handleRoleChange(form, userId) {
    const select = form.querySelector('.role-select');
    if (select.value === 'teacher') {
        window.location.href = `/admin/users/${userId}/promote-teacher`;
        return false;
    }
    return true;
}
</script>

@endsection
