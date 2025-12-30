@extends('layouts.teacher')

@section('title', 'My Students')
@section('header', 'My Students')

@section('content')

<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-900">My Students</h2>
    <p class="text-slate-600">All active students enrolled in your modules</p>
</div>

@forelse($modules as $module)
    @if($module->students->count())

    <div class="mb-10 rounded-2xl bg-white shadow border border-black/5">

        <!-- MODULE HEADER -->
        <div class="px-6 py-4 border-b bg-slate-50 rounded-t-2xl">
            <h3 class="text-lg font-semibold text-slate-900">
                {{ $module->name }}
            </h3>
            <p class="text-sm text-slate-500">
                Code: {{ $module->code ?? 'N/A' }}
            </p>
        </div>

        <!-- STUDENTS TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 text-slate-600">
                    <tr>
                        <th class="p-4 text-left">Name</th>
                        <th class="p-4 text-left">Email</th>
                        <th class="p-4 text-left">Enrolled On</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-left">Completed On</th>
                        <th class="p-4 text-left">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @foreach($module->students as $student)
                        <tr class="hover:bg-slate-50">
                            <td class="p-4 font-medium">
                                {{ $student->name }}
                            </td>

                            <td class="p-4 text-slate-600">
                                {{ $student->email }}
                            </td>

                            <!-- ENROLLED ON -->
                            <td class="p-4">
                                {{ $student->pivot->enrolled_at
                                    ? \Carbon\Carbon::parse($student->pivot->enrolled_at)->format('d M Y')
                                    : '—' }}
                            </td>

                            <!-- STATUS -->
                            <td class="p-4">
                                <span class="px-3 py-1 text-xs rounded-full bg-slate-100 text-slate-700">
                                    Pending
                                </span>
                            </td>

                            <!-- COMPLETED ON -->
                            <td class="p-4">—</td>

                            <!-- ACTIONS -->
                            <td class="p-4">
                                <div class="flex gap-2">

                                    <!-- PASS -->
                                    <form method="POST"
                                          action="{{ route('teacher.modules.students.pass', [$module->id, $student->id]) }}">
                                        @csrf
                                        <button
                                            onclick="return confirm('Mark {{ $student->name }} as PASS?')"
                                            class="px-4 py-2 text-xs font-bold text-white bg-green-600 rounded-lg hover:bg-green-700">
                                            PASS
                                        </button>
                                    </form>

                                    <!-- FAIL -->
                                    <form method="POST"
                                          action="{{ route('teacher.modules.students.fail', [$module->id, $student->id]) }}">
                                        @csrf
                                        <button
                                            onclick="return confirm('Mark {{ $student->name }} as FAIL?')"
                                            class="px-4 py-2 text-xs font-bold text-white bg-red-600 rounded-lg hover:bg-red-700">
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
    <div class="rounded-xl bg-white p-6 shadow text-slate-600">
        No modules assigned to you.
    </div>
@endforelse

@endsection
