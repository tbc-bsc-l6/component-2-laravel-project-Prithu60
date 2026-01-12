@extends('layouts.admin')

@section('content')

<!-- ================= COMPACT GRADIENT HEADER ================= -->
<div class="relative mb-6 overflow-hidden rounded-2xl shadow">
    <div class="relative h-28 md:h-32">

        <!-- Gradient (SAME AS MODULES) -->
        <div class="absolute inset-0
            bg-[linear-gradient(110deg,#00b84a_0%,#0b7a46_35%,#064a33_60%,#0a0a0a_100%)]">
        </div>

        <!-- Soft glow (SAME AS MODULES) -->
        <div class="absolute -left-24 -bottom-24 h-[280px] w-[280px]
            bg-[radial-gradient(circle_at_center,rgba(0,255,140,0.45)_0%,rgba(0,255,140,0)_60%)]
            blur-3xl">
        </div>

        <div class="absolute left-16 top-4 h-[220px] w-[220px]
            bg-[radial-gradient(circle_at_center,rgba(0,200,90,0.22)_0%,rgba(0,200,90,0)_60%)]
            blur-3xl">
        </div>

        <!-- Content -->
        <div class="relative z-10 flex h-full items-center justify-between px-6 md:px-8">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-white leading-tight">
                    Manage Teachers
                </h1>
                <p class="mt-1 text-xs md:text-sm text-white/85 max-w-2xl">
                    Create teachers, assign modules, and manage teaching responsibilities.
                </p>
            </div>

            <div class="hidden md:flex items-center justify-center
                        w-9 h-9 rounded-full bg-white/15 text-white text-base">
                👨‍🏫
            </div>
        </div>

    </div>
</div>




{{-- =========================
| FLASH MESSAGES
========================= --}}
@if(session('success'))
    <div class="mb-5 rounded-xl bg-green-50 border border-green-200 px-5 py-3 text-green-800 shadow-sm">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-5 rounded-xl bg-red-50 border border-red-200 px-5 py-3 text-red-800 shadow-sm">
        <ul class="list-disc pl-5 text-sm">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- =========================
| ADD TEACHER (SMALL & CUTE)
========================= --}}
<div class="mb-8 max-w-3xl rounded-3xl bg-white shadow border border-black/5">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900">
            Add New Teacher
        </h2>
        <p class="text-xs text-gray-500 mt-1 mb-4">
            Module assignment is optional.
        </p>

        <form method="POST" action="{{ route('admin.teachers.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input name="first_name" placeholder="First Name"
                       class="rounded-xl border-gray-200 px-4 py-2.5 text-sm focus:ring-emerald-500">

                <input name="last_name" placeholder="Last Name"
                       class="rounded-xl border-gray-200 px-4 py-2.5 text-sm focus:ring-emerald-500">
            </div>

            <input name="email" placeholder="Email"
                   class="w-full rounded-xl border-gray-200 px-4 py-2.5 text-sm focus:ring-emerald-500">

            <input type="password" name="password" placeholder="Password"
                   class="w-full rounded-xl border-gray-200 px-4 py-2.5 text-sm focus:ring-emerald-500">

            {{-- MODULES --}}
            <details class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
                <summary class="cursor-pointer font-semibold text-sm">
                    Assign Modules (optional)
                </summary>

                <div class="mt-3 space-y-2">
                    @foreach($modules as $module)
                        <label class="flex items-start gap-3 bg-white p-3 rounded-xl border">
                            <input type="checkbox" name="modules[]" value="{{ $module->id }}"
                                   class="mt-1 text-emerald-600">
                            <span class="text-sm font-medium">{{ $module->name }}</span>
                        </label>
                    @endforeach
                </div>
            </details>

            <div class="flex justify-end">
                <button class="rounded-xl bg-emerald-700 px-5 py-2.5 text-sm text-white font-semibold">
                    Create Teacher
                </button>
            </div>
        </form>
    </div>
</div>

{{-- =========================
| SEARCH
========================= --}}
<div class="mb-4 max-w-sm">
    <input
        id="teacherSearch"
        type="text"
        placeholder="Search teacher by name..."
        class="w-full rounded-xl border-gray-300 px-4 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
</div>

{{-- =========================
| TEACHER LIST
========================= --}}
<div class="rounded-3xl shadow-lg p-8 bg-gradient-to-br from-emerald-50 to-white border border-emerald-100">
    <div class="overflow-x-auto bg-white rounded-2xl border">
        <table class="w-full text-sm" id="teacherTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4 text-left">Name</th>
                    <th class="p-4 text-left">Email</th>
                    <th class="p-4 text-left">Modules</th>
                    <th class="p-4 text-left">Assigned Date</th>
                    <th class="p-4 text-left">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach($teachers as $teacher)
                    <tr class="hover:bg-emerald-50">
                        <td class="p-4 font-medium teacher-name">
                            {{ $teacher->name }}
                        </td>

                        <td class="p-4">{{ $teacher->email }}</td>

                        <td class="p-4">
                            <div class="flex flex-wrap gap-2">
                                @foreach($teacher->teachingModules as $m)
                                    <span class="px-3 py-1 bg-emerald-100 rounded-full text-xs">
                                        {{ $m->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>

                        {{-- ✅ FIXED ASSIGNED DATE --}}
                        <td class="p-4 text-gray-700">
                            @php
                                $assignedDate = $teacher->teachingModules
                                    ->pluck('pivot.teacher_assigned_at')
                                    ->filter()
                                    ->sort()
                                    ->first();
                            @endphp

                            @if($assignedDate)
                                <span class="text-xs font-semibold text-emerald-800
                                             bg-emerald-50 border border-emerald-100
                                             px-3 py-1 rounded-full">
                                    {{ \Carbon\Carbon::parse($assignedDate)->format('d M Y') }}
                                </span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>

                        <td class="p-4">
                            <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 text-sm hover:underline">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- =========================
| SEARCH SCRIPT
========================= --}}
<script>
document.getElementById('teacherSearch').addEventListener('keyup', function () {
    const value = this.value.toLowerCase();
    document.querySelectorAll('#teacherTable tbody tr').forEach(row => {
        const name = row.querySelector('.teacher-name').innerText.toLowerCase();
        row.style.display = name.includes(value) ? '' : 'none';
    });
});
</script>

@endsection
