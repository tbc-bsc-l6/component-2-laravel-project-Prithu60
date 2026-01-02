@extends('layouts.admin')

@section('content')

{{-- HEADER --}}
<div class="mb-8 rounded-2xl bg-gradient-to-r from-purple-200 to-purple-300 p-8">
    <h1 class="text-2xl font-bold text-purple-900">🎓 Old Students</h1>
    <p class="text-purple-800">
        Completed modules with PASS / FAIL history
    </p>
</div>

{{-- TABLE --}}
<div class="rounded-xl bg-white shadow">
    <table class="w-full text-sm">
        <thead class="bg-gray-100 text-gray-600">
            <tr>
                <th class="p-4 text-left">Student</th>
                <th class="p-4 text-left">Completed Modules</th>
                <th class="p-4 text-left">Results</th>
            </tr>
        </thead>

        <tbody class="divide-y">
        @forelse($students as $student)
            <tr>

                {{-- STUDENT --}}
                <td class="p-4">
                    <div class="font-semibold">{{ $student->name }}</div>
                    <div class="text-gray-500">{{ $student->email }}</div>
                </td>

                {{-- MODULES --}}
                <td class="p-4">
                    @forelse($student->modules as $module)
                        <div>{{ $module->name }}</div>
                    @empty
                        <span class="text-gray-400">No completed modules</span>
                    @endforelse
                </td>

                {{-- RESULTS --}}
                <td class="p-4 space-y-1">
                    @foreach($student->modules as $module)
                        <span class="inline-block rounded px-2 py-1 text-xs font-semibold
                            {{ $module->pivot->status === 'PASS'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700' }}">
                            {{ $module->pivot->status }}
                        </span>
                        <span class="text-xs text-gray-500">
                            ({{ \Carbon\Carbon::parse($module->pivot->completed_at)->format('d M Y') }})
                        </span>
                        <br>
                    @endforeach
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="3" class="p-6 text-center text-gray-500">
                    No old students found
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection
