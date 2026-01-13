@extends('layouts.teacher')

@section('title', 'Old Students')
@section('header', 'Old Students')

@section('content')

<!-- ================= HERO / GRADIENT HEADER ================= -->
<div class="mb-10">
    <div class="flex items-center justify-between
                px-5 py-5 rounded-2xl shadow-lg
                bg-gradient-to-r from-[#C7F000] via-[#8EE000] to-[#35B24A]
                text-white">

        <div>
            <h1 class="text-3xl font-bold">
                Completed Students 🎓
            </h1>
            <p class="mt-2 text-white/90">
                Student history with PASS / FAIL results
            </p>
        </div>

        <div class="hidden md:flex items-center justify-center
                    w-14 h-14 rounded-full bg-white/20 text-2xl">
            📜
        </div>
    </div>
</div>

@forelse($modules as $module)
    @if($module->students->count())

    <!-- ================= MODULE CARD ================= -->
    <div class="mb-10 rounded-2xl bg-white shadow
                border border-emerald-200">

        <!-- MODULE HEADER -->
        <div class="px-6 py-4 border-b border-emerald-100
                    bg-emerald-50/60 rounded-t-2xl
                    flex items-center justify-between">

            <div class="flex items-center gap-3">
                <span class="text-lg">🎓</span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">
                        {{ $module->name }}
                    </h3>
                    <p class="text-xs text-slate-500">
                        Code: {{ $module->code ?? 'N/A' }}
                    </p>
                </div>
            </div>

            <span class="px-3 py-1 text-xs font-semibold
                         rounded-full bg-emerald-100 text-emerald-700">
                {{ $module->students->count() }} students
            </span>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-emerald-50 text-emerald-900">
                    <tr>
                        <th class="p-4 text-left font-semibold">Name</th>
                        <th class="p-4 text-left font-semibold">Email</th>
                        <th class="p-4 text-left font-semibold">Enrolled On</th>
                        <th class="p-4 text-left font-semibold">Completed On</th>
                        <th class="p-4 text-left font-semibold">Result</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @foreach($module->students as $student)
                        <tr class="hover:bg-emerald-50/50 transition">

                            <!-- NAME -->
                            <td class="p-4 font-medium text-slate-900">
                                {{ $student->name }}
                            </td>

                            <!-- EMAIL -->
                            <td class="p-4 text-slate-600">
                                {{ $student->email }}
                            </td>

                            <!-- ENROLLED -->
                            <td class="p-4">
                                {{ $student->pivot->enrolled_at
                                    ? \Carbon\Carbon::parse($student->pivot->enrolled_at)->format('d M Y')
                                    : '—' }}
                            </td>

                            <!-- COMPLETED -->
                            <td class="p-4">
                                {{ $student->pivot->completed_at
                                    ? \Carbon\Carbon::parse($student->pivot->completed_at)->format('d M Y')
                                    : '—' }}
                            </td>

                            <!-- RESULT -->
                            <td class="p-4">
                                @if($student->pivot->status === 'PASS')
                                    <span class="px-3 py-1 text-xs font-semibold
                                                 rounded-full bg-green-100 text-green-700">
                                        PASS
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold
                                                 rounded-full bg-red-100 text-red-700">
                                        FAIL
                                    </span>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @endif
@empty
    <div class="bg-white p-6 rounded-xl shadow text-slate-600">
        No completed students yet.
    </div>
@endforelse

@endsection
