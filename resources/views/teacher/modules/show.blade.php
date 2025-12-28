@extends('layouts.teacher')

@section('title', 'Module Students')
@section('header', 'Module Students')

@section('content')

{{-- PAGE TITLE --}}
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-900">
        {{ $module->name }}
    </h2>
    <p class="text-slate-600">
        Manage student results for this module
    </p>
</div>

{{-- STUDENTS TABLE --}}
<div class="bg-white rounded-2xl shadow border border-black/5 overflow-hidden">

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
            @forelse($students as $student)
                <tr class="hover:bg-slate-50">

                    {{-- NAME --}}
                    <td class="p-4 font-medium text-slate-900">
                        {{ $student->name }}
                    </td>

                    {{-- EMAIL --}}
                    <td class="p-4 text-slate-600">
                        {{ $student->email }}
                    </td>

                    {{-- ENROLLED DATE --}}
                    <td class="p-4">
                        {{ $student->pivot->created_at->format('d M Y') }}
                    </td>

                    {{-- STATUS --}}
                    <td class="p-4">
                        @if($student->pivot->status === 'PASS')
                            <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">
                                PASS
                            </span>
                        @elseif($student->pivot->status === 'FAIL')
                            <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                FAIL
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs bg-slate-100 text-slate-600">
                                Pending
                            </span>
                        @endif
                    </td>

                    {{-- COMPLETED DATE --}}
                    <td class="p-4 text-slate-600">
                        {{ $student->pivot->completed_at
                            ? $student->pivot->completed_at->format('d M Y')
                            : '—' }}
                    </td>

                    {{-- ACTIONS --}}
                    <td class="p-4 flex gap-2">
                        <form method="POST"
                              action="{{ route('teacher.modules.students.pass', [$module, $student]) }}">
                            @csrf
                            <button
                                class="px-4 py-1 rounded-lg text-xs font-semibold
                                       bg-green-600 text-white hover:bg-green-700">
                                PASS
                            </button>
                        </form>

                        <form method="POST"
                              action="{{ route('teacher.modules.students.fail', [$module, $student]) }}">
                            @csrf
                            <button
                                class="px-4 py-1 rounded-lg text-xs font-semibold
                                       bg-red-600 text-white hover:bg-red-700">
                                FAIL
                            </button>
                        </form>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-6 text-center text-slate-500">
                        No students enrolled in this module.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

{{-- BACK BUTTON --}}
<div class="mt-6">
    <a href="{{ route('teacher.modules.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
              bg-slate-200 text-slate-700 hover:bg-slate-300 text-sm">
        ← Back to Modules
    </a>
</div>

@endsection
