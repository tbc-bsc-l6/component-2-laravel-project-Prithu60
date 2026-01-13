@extends('layouts.admin')

@section('content')

<!-- ================= COMPACT GRADIENT HEADER ================= -->
<div class="relative mb-6 overflow-hidden rounded-2xl shadow">
    <div class="relative h-28 md:h-32">

        <div class="absolute inset-0
            bg-[linear-gradient(110deg,#00b84a_0%,#0b7a46_35%,#064a33_60%,#0a0a0a_100%)]">
        </div>

        <div class="absolute -left-24 -bottom-24 h-[280px] w-[280px]
            bg-[radial-gradient(circle_at_center,rgba(0,255,140,0.45)_0%,rgba(0,255,140,0)_60%)]
            blur-3xl">
        </div>

        <div class="absolute left-16 top-4 h-[220px] w-[220px]
            bg-[radial-gradient(circle_at_center,rgba(0,200,90,0.22)_0%,rgba(0,200,90,0)_60%)]
            blur-3xl">
        </div>

        <div class="relative z-10 flex h-full items-center justify-between px-6 md:px-8">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-white">
                    Manage Teachers
                </h1>
                <p class="mt-1 text-xs md:text-sm text-white/85">
                    Create teachers, assign modules, and manage role changes.
                </p>
            </div>

            <div class="hidden md:flex w-9 h-9 rounded-full bg-white/15 text-white items-center justify-center">
                👨‍🏫
            </div>
        </div>
    </div>
</div>

{{-- FLASH --}}
@if(session('success'))
    <div class="mb-4 rounded-xl bg-green-50 border border-green-200 px-5 py-3 text-green-800">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 rounded-xl bg-red-50 border border-red-200 px-5 py-3 text-red-800">
        <ul class="list-disc pl-5 text-sm">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- ================= ADD TEACHER (RESTORED) ================= --}}
<div class="mb-8 max-w-3xl rounded-3xl bg-white shadow border">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900">Add New Teacher</h2>
        <p class="text-xs text-gray-500 mt-1 mb-4">Module assignment is optional.</p>

        <form method="POST" action="{{ route('admin.teachers.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input name="first_name" placeholder="First Name"
                       class="rounded-xl border-gray-300 px-4 py-2 text-sm" required>

                <input name="last_name" placeholder="Last Name"
                       class="rounded-xl border-gray-300 px-4 py-2 text-sm" required>
            </div>

            <input name="email" placeholder="Email"
                   class="w-full rounded-xl border-gray-300 px-4 py-2 text-sm" required>

            <input type="password" name="password" placeholder="Password"
                   class="w-full rounded-xl border-gray-300 px-4 py-2 text-sm" required>

            <details class="rounded-xl border bg-emerald-50 p-4">
                <summary class="cursor-pointer font-semibold text-sm">
                    Assign Modules (optional)
                </summary>

                <div class="mt-3 space-y-2">
                    @foreach($modules as $module)
                        <label class="flex gap-2 items-center">
                            <input type="checkbox" name="modules[]" value="{{ $module->id }}">
                            <span class="text-sm">{{ $module->name }}</span>
                        </label>
                    @endforeach
                </div>
            </details>

            <div class="flex justify-end">
                <button class="rounded-xl bg-emerald-700 px-5 py-2 text-sm text-white">
                    Create Teacher
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SEARCH --}}
<div class="mb-4 max-w-sm">
    <input id="teacherSearch" type="text"
           placeholder="Search teacher by name..."
           class="w-full rounded-xl border-gray-300 px-4 py-2 text-sm">
</div>

{{-- TEACHER TABLE --}}
<div class="rounded-3xl shadow p-6 bg-white border">
    <table class="w-full text-sm" id="teacherTable">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-4 text-left">Name</th>
                <th class="p-4 text-left">Email</th>
                <th class="p-4 text-left">Modules</th>
                <th class="p-4 text-left">Assigned</th>
                <th class="p-4 text-left">Actions</th>
            </tr>
        </thead>

        <tbody class="divide-y">
            @foreach($teachers as $teacher)
            <tr class="hover:bg-emerald-50">
                <td class="p-4 teacher-name">{{ $teacher->name }}</td>
                <td class="p-4">{{ $teacher->email }}</td>

                <td class="p-4">
                    @foreach($teacher->teachingModules as $m)
                        <span class="px-3 py-1 bg-emerald-100 rounded-full text-xs">
                            {{ $m->name }}
                        </span>
                    @endforeach
                </td>

                <td class="p-4 text-xs text-gray-600">
                    @php
                        $date = $teacher->teachingModules
                            ->pluck('pivot.teacher_assigned_at')
                            ->filter()
                            ->first();
                    @endphp
                    {{ $date ? \Carbon\Carbon::parse($date)->format('d M Y') : 'N/A' }}
                </td>

                <td class="p-4 space-y-1">
                    <form method="POST"
                          action="{{ route('admin.users.demote-student', $teacher) }}"
                          onsubmit="return confirm('Change this teacher to student?')">
                        @csrf
                        @method('PATCH')
                        <button class="text-blue-600 text-xs hover:underline">
                            Change to Student
                        </button>
                    </form>

                    <form method="POST"
                          action="{{ route('admin.teachers.destroy', $teacher) }}"
                          onsubmit="return confirm('Delete permanently?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 text-xs hover:underline">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
document.getElementById('teacherSearch').addEventListener('keyup', function () {
    const value = this.value.toLowerCase();
    document.querySelectorAll('#teacherTable tbody tr').forEach(row => {
        row.style.display = row.querySelector('.teacher-name')
            .innerText.toLowerCase().includes(value) ? '' : 'none';
    });
});
</script>

@endsection
