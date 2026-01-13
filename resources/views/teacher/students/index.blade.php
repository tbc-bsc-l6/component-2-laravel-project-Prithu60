@extends('layouts.teacher')

@section('title', 'My Students')
@section('header', 'My Students')

@section('content')

<!-- ================= HERO HEADER ================= -->
<div class="mb-8">
    <div class="flex items-center justify-between
                px-5 py-5 rounded-2xl shadow-lg
                bg-gradient-to-r from-[#C7F000] via-[#8EE000] to-[#35B24A]
                text-white">

        <div>
            <h2 class="text-3xl font-bold">
                My Students 👥
            </h2>
            <p class="mt-2 text-white/90 text-sm">
                All active students enrolled in your modules
            </p>
        </div>

        <div class="hidden md:flex items-center justify-center
                    w-14 h-14 rounded-full bg-white/20 text-2xl">
            🎓
        </div>
    </div>
</div>


@forelse($modules as $module)
    @if($module->students->count())

    <!-- MODULE CARD -->
    <div class="mb-6 rounded-2xl bg-white shadow
                border border-emerald-200">

        <!-- MODULE GRADIENT HEADER -->
        <div class="px-5 py-3 rounded-t-2xl
                    bg-gradient-to-r
                    from-emerald-100 via-emerald-50 to-white
                    border-b border-emerald-200">

            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-emerald-900">
                        {{ $module->name }}
                    </h3>
                    <p class="text-xs text-emerald-700">
                        Code: {{ $module->code ?? 'N/A' }}
                    </p>
                </div>

                <span class="text-xs font-medium text-emerald-700
                             bg-emerald-100 px-3 py-1 rounded-full">
                    {{ $module->students->count() }} students
                </span>
            </div>
        </div>

        <!-- STUDENTS TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-emerald-50 text-emerald-800">
                    <tr>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Enrolled</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Completed</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @foreach($module->students as $student)
                        <tr class="hover:bg-emerald-50/50">

                            <!-- NAME -->
                            <td class="px-4 py-2 font-medium text-slate-900">
                                {{ $student->name }}
                            </td>

                            <!-- EMAIL -->
                            <td class="px-4 py-2 text-slate-600">
                                {{ $student->email }}
                            </td>

                            <!-- ENROLLED -->
                            <td class="px-4 py-2 text-slate-600">
                                {{ $student->pivot->enrolled_at
                                    ? \Carbon\Carbon::parse($student->pivot->enrolled_at)->format('d M Y')
                                    : '—' }}
                            </td>

                            <!-- STATUS -->
                            <td class="px-4 py-2">
                                <span class="px-3 py-0.5 text-xs rounded-full
                                             bg-slate-200 text-slate-700">
                                    Pending
                                </span>
                            </td>

                            <!-- COMPLETED -->
                            <td class="px-4 py-2 text-slate-600">
                                —
                            </td>

                            <!-- ACTIONS -->
                            <td class="px-4 py-2">
                                <div class="flex gap-2">

                                    <!-- PASS -->
                                    <form method="POST"
                                          action="{{ route('teacher.modules.students.pass', [$module->id, $student->id]) }}">
                                        @csrf
                                        <button
                                            onclick="return confirm('Mark {{ $student->name }} as PASS?')"
                                            class="px-3 py-1 text-xs font-semibold
                                                   text-white bg-green-600 rounded-md
                                                   hover:bg-green-700">
                                            PASS
                                        </button>
                                    </form>

                                    <!-- FAIL -->
                                    <form method="POST"
                                          action="{{ route('teacher.modules.students.fail', [$module->id, $student->id]) }}">
                                        @csrf
                                        <button
                                            onclick="return confirm('Mark {{ $student->name }} as FAIL?')"
                                            class="px-3 py-1 text-xs font-semibold
                                                   text-white bg-red-600 rounded-md
                                                   hover:bg-red-700">
                                            FAIL
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
    @endif
@empty
    <div class="rounded-xl bg-white p-5 shadow text-slate-600">
        No modules assigned to you.
    </div>
@endforelse

@endsection
