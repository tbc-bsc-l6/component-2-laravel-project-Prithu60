@extends('layouts.admin')

@section('content')

<!-- ================= COMPACT GRADIENT HEADER ================= -->
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
                    Manage Modules
                </h1>
                <p class="mt-1 text-xs md:text-sm text-white/85 max-w-2xl">
                    Create modules, assign teachers, manage students, and control availability.
                </p>
            </div>

            <div class="hidden md:flex items-center justify-center
                        w-9 h-9 rounded-full bg-white/15 text-white text-base">
                📘
            </div>
        </div>
    </div>
</div>

{{-- ================= SUCCESS MESSAGE ================= --}}
@if(session('success'))
    <div class="mb-4 rounded-xl bg-emerald-100 px-4 py-3 text-emerald-900 shadow-sm border border-emerald-200 text-sm">
        <span class="font-semibold">Success:</span> {{ session('success') }}
    </div>
@endif

{{-- ================= INSTANT SEARCH (MATCH OTHER PAGES) ================= --}}
<div class="mb-4 max-w-sm">
    <input
        id="moduleSearch"
        type="text"
        placeholder="Search module by name..."
        class="w-full rounded-xl border-gray-300 px-4 py-2 text-sm
               focus:ring-emerald-500 focus:border-emerald-500">
</div>

{{-- ================= ADD MODULE ================= --}}
<div class="mb-6 max-w-3xl rounded-2xl bg-white p-4 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-3">
        <div>
            <h2 class="text-sm font-bold text-gray-900">Add New Module</h2>
            <p class="text-xs text-gray-500">Name + optional description.</p>
        </div>

        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-50 text-emerald-700 text-sm">
            ➕
        </div>
    </div>

    <form method="POST" action="{{ route('admin.modules.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
        @csrf

        <div class="md:col-span-1">
            <label class="block text-xs font-semibold text-gray-700">Module Name</label>
            <input
                name="name"
                value="{{ old('name') }}"
                class="mt-1 w-full rounded-xl border-gray-300 px-3 py-2 text-sm
                       focus:border-emerald-500 focus:ring-emerald-500"
                placeholder="e.g. Web Development"
            >
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-gray-700">Description</label>
            <textarea
                name="description"
                rows="2"
                class="mt-1 w-full rounded-xl border-gray-300 px-3 py-2 text-sm
                       focus:border-emerald-500 focus:ring-emerald-500"
                placeholder="Optional..."
            >{{ old('description') }}</textarea>
        </div>

        <div class="md:col-span-3 flex justify-end">
            <button
                class="rounded-xl bg-emerald-700 px-4 py-2 text-sm font-semibold text-white
                       hover:bg-emerald-800 transition">
                Create Module
            </button>
        </div>
    </form>
</div>

{{-- ================= ALL MODULES ================= --}}
<div class="rounded-3xl bg-emerald-50/70 border border-emerald-100 p-6 shadow-sm">

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-900">All Modules</h2>
        <span class="text-sm text-gray-500">
            Total: <span class="font-semibold text-gray-900">{{ $modules->count() }}</span>
        </span>
    </div>

    <div class="overflow-x-auto rounded-2xl bg-white shadow-sm border border-gray-100">
        <table class="min-w-full text-sm" id="modulesTable">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Name</th>
                    <th class="px-6 py-4 text-left font-semibold">Students</th>
                    <th class="px-6 py-4 text-left font-semibold">Teachers</th>
                    <th class="px-6 py-4 text-left font-semibold">Status</th>
                    <th class="px-6 py-4 text-left font-semibold">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">
            @foreach($modules as $module)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900 module-name">
                            {{ $module->name }}
                        </div>
                        @if($module->description)
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $module->description }}
                            </div>
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">
                            Active: {{ $module->active_students_count }} / 10
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            Completed: {{ $module->completed_students_count }}
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <span class="font-semibold text-gray-900">
                            {{ $module->teachers_count }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        @if($module->is_active)
                            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 rounded-full bg-gray-200 px-3 py-1 text-xs font-semibold text-gray-700">
                                <span class="h-2 w-2 rounded-full bg-gray-500"></span>
                                Archived
                            </span>
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.modules.students', $module) }}"
                               class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium hover:bg-gray-100">
                                Students
                            </a>

                            <a href="{{ route('admin.modules.assign-teachers', $module) }}"
                               class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium hover:bg-gray-100">
                                Assign
                            </a>

                            <a href="{{ route('admin.modules.edit', $module) }}"
                               class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium hover:bg-gray-100">
                                Edit
                            </a>
                        </div>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ================= SEARCH SCRIPT ================= --}}
<script>
document.getElementById('moduleSearch').addEventListener('keyup', function () {
    const value = this.value.toLowerCase();

    document.querySelectorAll('#modulesTable tbody tr').forEach(row => {
        const name = row.querySelector('.module-name').innerText.toLowerCase();
        row.style.display = name.includes(value) ? '' : 'none';
    });
});
</script>

@endsection
