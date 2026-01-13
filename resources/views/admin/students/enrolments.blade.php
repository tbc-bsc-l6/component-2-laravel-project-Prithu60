@extends('layouts.admin')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="mb-8 rounded-2xl bg-gradient-to-r from-emerald-200 to-lime-200 p-8">
    <h1 class="text-2xl font-bold text-emerald-900">
        📘 Enrolments – {{ $student->name }}
    </h1>
    <p class="text-emerald-800">
        View enrolment history and manage active modules
    </p>
</div>

{{-- ================= FLASH ================= --}}
@if(session('success'))
    <div class="mb-6 rounded bg-green-100 px-4 py-3 text-green-800">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 rounded bg-red-100 px-4 py-3 text-red-800">
        {{ session('error') }}
    </div>
@endif

{{-- ================= TABLE ================= --}}
<div class="rounded-xl bg-white shadow">
    <table class="w-full text-sm">
        <thead class="bg-gray-100 text-gray-600">
            <tr>
                <th class="p-4 text-left">Module</th>
                <th class="p-4 text-left">Enrolled At</th>
                <th class="p-4 text-left">Status</th>
                <th class="p-4 text-left">Completed At</th>
                <th class="p-4 text-left">Action</th>
            </tr>
        </thead>

        <tbody class="divide-y">
        @forelse($student->modules as $module)
            <tr>
                <td class="p-4 font-semibold text-gray-900">
                    {{ $module->name }}
                </td>

                <td class="p-4 text-gray-600">
                    {{ \Carbon\Carbon::parse($module->pivot->enrolled_at)->format('d M Y') }}
                </td>

                <td class="p-4">
                    @if($module->pivot->status)
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                            {{ $module->pivot->status === 'PASS'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700' }}">
                            {{ $module->pivot->status }}
                        </span>
                    @else
                        <span class="text-yellow-700 text-xs font-semibold">
                            ACTIVE
                        </span>
                    @endif
                </td>

                <td class="p-4 text-gray-600">
                    {{ $module->pivot->completed_at
                        ? \Carbon\Carbon::parse($module->pivot->completed_at)->format('d M Y')
                        : '-' }}
                </td>

                <td class="p-4">
                    @if($module->pivot->completed_at === null)
                        <form method="POST"
                              action="{{ route('admin.students.removeFromModule', [$student, $module]) }}">
                            @csrf
                            @method('DELETE')

                            <button
                                class="rounded bg-red-600 px-3 py-1 text-xs text-white hover:bg-red-700">
                                Remove
                            </button>
                        </form>
                    @else
                        <span class="text-xs text-gray-400">Locked</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="p-6 text-center text-gray-500">
                    No enrolments found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<a href="{{ route('admin.students.index') }}"
   class="mt-6 inline-block text-sm text-emerald-700 hover:underline">
    ← Back to Students
</a>

@endsection
