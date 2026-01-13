@extends('layouts.teacher')

@section('title', 'Module Students')
@section('header', 'Module Students')

@section('content')

<!-- ================= HERO SECTION ================= -->
<div class="mb-10">
    <div class="flex items-center justify-between
                px-5 py-5 rounded-2xl shadow-lg
                bg-gradient-to-r from-[#C7F000] via-[#8EE000] to-[#35B24A]
                text-white">

        <div>
            <h1 class="text-3xl font-bold leading-tight">
                {{ $module->name }}
            </h1>
            <p class="mt-2 text-white/90">
                Manage student results for this module
            </p>
        </div>

        <div class="hidden md:flex items-center justify-center
                    w-14 h-14 rounded-full bg-white/20 text-2xl">
            📘
        </div>
    </div>
</div>

<!-- ================= STUDENTS TABLE ================= -->
<div class="bg-white rounded-2xl shadow border border-black/5 overflow-hidden">

    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600">
            <tr>
                <th class="p-4 text-left font-semibold">Name</th>
                <th class="p-4 text-left font-semibold">Email</th>
                <th class="p-4 text-left font-semibold">Enrolled On</th>
                <th class="p-4 text-left font-semibold">Status</th>
                <th class="p-4 text-left font-semibold">Completed On</th>
                <th class="p-4 text-left font-semibold">Actions</th>
            </tr>
        </thead>

        <tbody class="divide-y">
            @forelse($students as $student)
                <tr class="hover:bg-slate-50 transition">

                    <!-- NAME -->
                    <td class="p-4 font-medium text-slate-900">
                        {{ $student->name }}
                    </td>

                    <!-- EMAIL -->
                    <td class="p-4 text-slate-600">
                        {{ $student->email }}
                    </td>

                    <!-- ENROLLED DATE -->
                    <td class="p-4 text-slate-600">
                        {{ $student->pivot->created_at->format('d M Y') }}
                    </td>

                    <!-- STATUS -->
                    <td class="p-4">
                        @if($student->pivot->status === 'PASS')
                            <span class="px-3 py-1 rounded-full text-xs
                                         bg-green-100 text-green-700 font-semibold">
                                PASS
                            </span>
                        @elseif($student->pivot->status === 'FAIL')
                            <span class="px-3 py-1 rounded-full text-xs
                                         bg-red-100 text-red-700 font-semibold">
                                FAIL
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs
                                         bg-slate-100 text-slate-600 font-semibold">
                                Pending
                            </span>
                        @endif
                    </td>

                    <!-- COMPLETED DATE -->
                    <td class="p-4 text-slate-600">
                        {{ $student->pivot->completed_at
                            ? $student->pivot->completed_at->format('d M Y')
                            : '—' }}
                    </td>

                    <!-- ACTIONS -->
                    <td class="p-4">
                        <div class="flex gap-2">

                            <form method="POST"
                                  action="{{ route('teacher.modules.students.pass', [$module, $student]) }}">
                                @csrf
                                <button
                                    class="px-4 py-1.5 rounded-lg text-xs font-semibold
                                           bg-green-600 text-white
                                           hover:bg-green-700 transition">
                                    PASS
                                </button>
                            </form>

                            <form method="POST"
                                  action="{{ route('teacher.modules.students.fail', [$module, $student]) }}">
                                @csrf
                                <button
                                    class="px-4 py-1.5 rounded-lg text-xs font-semibold
                                           bg-red-600 text-white
                                           hover:bg-red-700 transition">
                                    FAIL
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-500">
                        No students enrolled in this module.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

<!-- ================= BACK BUTTON ================= -->
<div class="mt-8">
    <a href="{{ route('teacher.modules.index') }}"
       class="inline-flex items-center gap-2
              px-5 py-3 rounded-xl
              bg-slate-900 text-white
              hover:bg-slate-800 transition shadow">
        ← Back to Modules
    </a>
</div>

@endsection
