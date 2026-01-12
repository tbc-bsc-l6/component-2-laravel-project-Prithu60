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
                    Old Students
                </h1>
                <p class="mt-1 text-xs md:text-sm text-white/85 max-w-2xl">
                    Completed modules with PASS / FAIL history
                </p>
            </div>

            <div class="hidden md:flex items-center justify-center
                        w-9 h-9 rounded-full bg-white/15 text-white text-base">
                🎓
            </div>
        </div>
    </div>
</div>

{{-- ================= SEARCH ================= --}}
<div class="mb-4 max-w-sm">
    <input
        id="oldStudentSearch"
        type="text"
        placeholder="Search old student by name..."
        class="w-full rounded-xl border-gray-300 px-4 py-2 text-sm
               focus:ring-emerald-500 focus:border-emerald-500">
</div>

{{-- ================= CONTENT CARD ================= --}}
<div class="rounded-3xl bg-emerald-50/70 border border-emerald-100 p-6 shadow-sm">

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-gray-100">
        <table class="w-full text-sm" id="oldStudentsTable">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Student</th>
                    <th class="px-6 py-4 text-left font-semibold">Completed Modules</th>
                    <th class="px-6 py-4 text-left font-semibold">Results</th>
                </tr>
            </thead>

            <tbody class="divide-y">
            @forelse($students as $student)
                <tr class="hover:bg-gray-50 transition">

                    {{-- STUDENT --}}
                    <td class="px-6 py-5">
                        <div class="font-semibold text-gray-900 old-student-name">
                            {{ $student->name }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $student->email }}
                        </div>
                    </td>

                    {{-- COMPLETED MODULES --}}
                    <td class="px-6 py-5 space-y-1">
                        @forelse($student->modules as $module)
                            <div class="text-gray-800">
                                {{ $module->name }}
                            </div>
                        @empty
                            <span class="italic text-gray-400">
                                No completed modules
                            </span>
                        @endforelse
                    </td>

                    {{-- RESULTS --}}
                    <td class="px-6 py-5 space-y-2">
                        @foreach($student->modules as $module)
                            <div class="flex items-center gap-2">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                    {{ $module->pivot->status === 'PASS'
                                        ? 'bg-emerald-100 text-emerald-700'
                                        : 'bg-red-100 text-red-700' }}">
                                    {{ $module->pivot->status }}
                                </span>

                                <span class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($module->pivot->completed_at)->format('d M Y') }}
                                </span>
                            </div>
                        @endforeach
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                        No old students found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- ================= SEARCH SCRIPT ================= --}}
<script>
document.getElementById('oldStudentSearch').addEventListener('keyup', function () {
    const value = this.value.toLowerCase();

    document.querySelectorAll('#oldStudentsTable tbody tr').forEach(row => {
        const name = row.querySelector('.old-student-name')?.innerText.toLowerCase() || '';
        row.style.display = name.includes(value) ? '' : 'none';
    });
});
</script>

@endsection
