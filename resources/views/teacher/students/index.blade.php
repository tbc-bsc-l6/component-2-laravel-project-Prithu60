@extends('layouts.teacher')

@section('title', 'My Students')
@section('header', 'My Students')

@section('content')

<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-900">My Students</h2>
    <p class="text-slate-600">All students enrolled in your modules</p>
</div>

@forelse($modules as $module)
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
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($module->students as $student)
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
                                @if($student->pivot->status === 'PASS')
                                    <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                        PASS
                                    </span>
                                @elseif($student->pivot->status === 'FAIL')
                                    <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                        FAIL
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs rounded-full bg-slate-100 text-slate-700">
                                        Pending
                                    </span>
                                @endif
                            </td>

                            <!-- COMPLETED ON -->
                            <td class="p-4">
                                {{ $student->pivot->completed_at
                                    ? \Carbon\Carbon::parse($student->pivot->completed_at)->format('d M Y')
                                    : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-500">
                                No students enrolled.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="rounded-xl bg-white p-6 shadow text-slate-600">
        No modules assigned to you.
    </div>
@endforelse

@endsection
