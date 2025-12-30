@extends('layouts.teacher')

@section('title', 'Old Students')
@section('header', 'Old Students')

@section('content')

<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-900">Completed Students</h2>
    <p class="text-slate-600">Student history with PASS / FAIL results</p>
</div>

@forelse($modules as $module)
    @if($module->students->count())

    <div class="mb-10 rounded-2xl bg-white shadow border border-black/5">

        <!-- MODULE HEADER -->
        <div class="px-6 py-4 border-b bg-slate-50 rounded-t-2xl flex items-center gap-2">
            🎓
            <h3 class="text-lg font-semibold text-slate-900">
                {{ $module->name }}
            </h3>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 text-slate-600">
                    <tr>
                        <th class="p-4 text-left">Name</th>
                        <th class="p-4 text-left">Email</th>
                        <th class="p-4 text-left">Enrolled On</th>
                        <th class="p-4 text-left">Completed On</th>
                        <th class="p-4 text-left">Result</th>
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

                            <!-- ENROLLED DATE -->
                            <td class="p-4">
                                {{ $student->pivot->enrolled_at
                                    ? \Carbon\Carbon::parse($student->pivot->enrolled_at)->format('d M Y')
                                    : '—' }}
                            </td>

                            <!-- COMPLETED DATE -->
                            <td class="p-4">
                                {{ $student->pivot->completed_at
                                    ? \Carbon\Carbon::parse($student->pivot->completed_at)->format('d M Y')
                                    : '—' }}
                            </td>

                            <!-- RESULT -->
                            <td class="p-4">
                                @if($student->pivot->status === 'PASS')
                                    <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                        PASS
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700">
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
