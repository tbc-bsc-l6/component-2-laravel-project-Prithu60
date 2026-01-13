@extends('layouts.admin')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="relative mb-6 overflow-hidden rounded-2xl shadow">
    <div class="relative h-28 md:h-32">
        <div class="absolute inset-0 bg-[linear-gradient(110deg,#00b84a_0%,#0b7a46_35%,#064a33_60%,#0a0a0a_100%)]"></div>

        <div class="relative z-10 flex h-full items-center justify-between px-6 md:px-8">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-white leading-tight">
                    Old Students
                </h1>
                <p class="mt-1 text-xs md:text-sm text-white/85">
                    Completed modules with PASS / FAIL history (Admin can update roles)
                </p>
            </div>
        </div>
    </div>
</div>

{{-- FLASH --}}
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

{{-- SEARCH --}}
<div class="mb-4 max-w-sm">
    <input
        id="oldStudentSearch"
        type="text"
        placeholder="Search old student by name or email..."
        class="w-full rounded-xl border-gray-300 px-4 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500">
</div>

{{-- TABLE CARD --}}
<div class="rounded-3xl bg-emerald-50/70 border border-emerald-100 p-6 shadow-sm">
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-gray-100">
        <table class="w-full text-sm" id="oldStudentsTable">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Student</th>
                    <th class="px-6 py-4 text-left font-semibold">Completed Modules</th>
                    <th class="px-6 py-4 text-left font-semibold">Results</th>
                    <th class="px-6 py-4 text-left font-semibold">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">
            @forelse($students as $student)

                @php
                    // group completed modules for display
                    $completed = $student->modules ?? collect();
                @endphp

                <tr class="hover:bg-gray-50 transition">

                    {{-- STUDENT --}}
                    <td class="px-6 py-5">
                        <div class="font-semibold text-gray-900 old-student-name">
                            {{ $student->name }}
                        </div>
                        <div class="text-xs text-gray-500 old-student-email">
                            {{ $student->email }}
                        </div>

                        <div class="mt-2">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-purple-100 text-purple-700">
                                Old student
                            </span>
                        </div>
                    </td>

                    {{-- COMPLETED MODULES --}}
                    <td class="px-6 py-5 text-gray-800">
                        @if($completed->count())
                            <ul class="space-y-1">
                                @foreach($completed as $m)
                                    <li>{{ $m->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-xs text-gray-400">No completed modules</span>
                        @endif
                    </td>

                    {{-- RESULTS --}}
                    <td class="px-6 py-5">
                        @if($completed->count())
                            <div class="space-y-2">
                                @foreach($completed as $m)
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="font-medium text-gray-700">{{ $m->name }}</span>

                                        @if($m->pivot->status === 'PASS')
                                            <span class="rounded bg-emerald-100 px-2 py-1 text-emerald-700">
                                                PASS
                                            </span>
                                        @elseif($m->pivot->status === 'FAIL')
                                            <span class="rounded bg-red-100 px-2 py-1 text-red-700">
                                                FAIL
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif

                                        @if($m->pivot->completed_at)
                                            <span class="text-gray-400">
                                                {{ \Carbon\Carbon::parse($m->pivot->completed_at)->format('d M Y') }}
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span class="text-xs text-gray-400">No results</span>
                        @endif
                    </td>

                    {{-- ACTIONS --}}
                    <td class="px-6 py-5 space-y-2">
                        <form method="POST"
                              action="{{ route('admin.students.updateRole', $student) }}"
                              onsubmit="return handleRoleChangeOld(this, {{ $student->id }})"
                              class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')

                            {{-- NOTE: old-students page only needs Student/Old Student + Teacher --}}
                            <select name="role_id"
                                    class="rounded-lg border-gray-300 px-2 py-1 text-sm role-select-old
                                           focus:ring-emerald-500">
                                {{-- keep these values consistent with your roles table IDs --}}
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ $student->user_role_id === $role->id ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_',' ', $role->role)) }}
                                    </option>
                                @endforeach

                                {{-- Teacher special option --}}
                                <option value="teacher">Teacher</option>
                            </select>

                            <button type="submit"
                                    class="rounded-lg bg-gray-900 px-3 py-1 text-sm text-white hover:bg-black transition">
                                Update
                            </button>
                        </form>
                    </td>

                </tr>

            @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                        No old students found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- SEARCH SCRIPT --}}
<script>
document.getElementById('oldStudentSearch').addEventListener('keyup', function () {
    const value = this.value.toLowerCase();

    document.querySelectorAll('#oldStudentsTable tbody tr').forEach(row => {
        const name  = row.querySelector('.old-student-name')?.innerText.toLowerCase() ?? '';
        const email = row.querySelector('.old-student-email')?.innerText.toLowerCase() ?? '';
        row.style.display = (name.includes(value) || email.includes(value)) ? '' : 'none';
    });
});

// if Teacher chosen -> redirect to module assignment page
function handleRoleChangeOld(form, userId) {
    const select = form.querySelector('.role-select-old');
    if (select && select.value === 'teacher') {
        window.location.href = `/admin/users/${userId}/promote-teacher`;
        return false;
    }
    return true;
}
</script>

@endsection
