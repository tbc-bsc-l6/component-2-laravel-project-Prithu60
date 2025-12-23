@extends('layouts.admin')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        Students in {{ $module->name }}
    </h1>
    <p class="text-gray-500">
        {{ $module->students->count() }} / 10 enrolled
    </p>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-4 py-3 text-left">Name</th>
                <th class="px-4 py-3 text-left">Email</th>
                <th class="px-4 py-3 text-left">Start Date</th>
                <th class="px-4 py-3 text-left">Result</th>
                <th class="px-4 py-3 text-left">Completion</th>
                <th class="px-4 py-3 text-left">Action</th>
            </tr>
        </thead>

        <tbody class="divide-y">
            @forelse($students as $student)
                <tr>
                    <td class="px-4 py-3 font-medium">
                        {{ $student->name }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $student->email }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $student->pivot->student_start_date ?? '—' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $student->pivot->pass_fail ?? 'Pending' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $student->pivot->completion_date ?? '—' }}
                    </td>

                    <td class="px-4 py-3">
                        <form method="POST"
                              action="{{ route('admin.students.removeFromModule', [$student->id, $module->id]) }}"
                              onsubmit="return confirm('Remove student from this module?')">
                            @csrf
                            @method('DELETE')

                            <button class="rounded-lg border border-red-200 px-3 py-1 text-red-700 hover:bg-red-50">
                                Remove
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                        No students enrolled
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
