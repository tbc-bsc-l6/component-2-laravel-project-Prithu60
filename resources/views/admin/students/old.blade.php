@extends('layouts.admin')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="mb-10 rounded-3xl bg-gradient-to-r from-green-200 via-emerald-200 to-lime-200 p-8 shadow-sm">
    <h1 class="text-3xl font-bold text-emerald-900 flex items-center gap-2">
        🎓 Old Students
    </h1>
    <p class="mt-2 text-emerald-800">
        Completed modules with PASS / FAIL history
    </p>
</div>

{{-- ================= CONTENT CARD ================= --}}
<div class="rounded-2xl bg-white shadow-lg overflow-hidden">

    <table class="w-full text-sm">
        <thead class="bg-emerald-50 text-emerald-800">
            <tr>
                <th class="px-6 py-4 text-left font-semibold">Student</th>
                <th class="px-6 py-4 text-left font-semibold">Completed Modules</th>
                <th class="px-6 py-4 text-left font-semibold">Results</th>
            </tr>
        </thead>

        <tbody class="divide-y">
        @forelse($students as $student)
            <tr class="hover:bg-emerald-50/40 transition">

                {{-- STUDENT --}}
                <td class="px-6 py-5">
                    <div class="font-semibold text-gray-900">
                        {{ $student->name }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $student->email }}
                    </div>
                </td>

                {{-- MODULES --}}
                <td class="px-6 py-5 space-y-1">
                    @forelse($student->modules as $module)
                        <div class="text-gray-800">
                            {{ $module->name }}
                        </div>
                    @empty
                        <span class="italic text-gray-400">
                            No completed modules
                        </span>
                    @endforelse
                </td>

                {{-- RESULTS --}}
                <td class="px-6 py-5 space-y-2">
                    @foreach($student->modules as $module)
                        <div class="flex items-center gap-2">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                {{ $module->pivot->status === 'PASS'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-red-100 text-red-700' }}">
                                {{ $module->pivot->status }}
                            </span>

                            <span class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($module->pivot->completed_at)->format('d M Y') }}
                            </span>
                        </div>
                    @endforeach
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                    No old students found
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection
