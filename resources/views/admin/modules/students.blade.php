@extends('layouts.admin')

@section('content')

<!-- ================= COMPACT GREEN GRADIENT HEADER ================= -->
<div class="relative mb-6 overflow-hidden rounded-2xl shadow">
    <div class="relative h-24 md:h-28">

        <!-- Gradient -->
        <div class="absolute inset-0 bg-[linear-gradient(110deg,#00b84a_0%,#0b7a46_40%,#064a33_65%,#0a0a0a_100%)]"></div>

        <!-- Soft glow -->
        <div class="absolute -left-20 -bottom-20 h-[240px] w-[240px]
                    bg-[radial-gradient(circle_at_center,rgba(0,255,140,0.35)_0%,rgba(0,255,140,0)_60%)]
                    blur-3xl"></div>

        <!-- Content -->
        <div class="relative z-10 flex h-full items-center justify-between px-6">
            <div>
                <h1 class="text-lg md:text-xl font-bold text-white">
                    Students in {{ $module->name }}
                </h1>
                <p class="mt-1 text-xs md:text-sm text-white/85">
                    {{ $students->count() }} / 10 enrolled
                </p>
            </div>

            <div class="hidden md:flex items-center justify-center
                        rounded-full bg-white/15 px-3 py-1 text-xs text-white font-semibold">
                Module
            </div>
        </div>
    </div>
</div>

{{-- ================= STUDENTS LIST (TEACHER-LIST STYLE) ================= --}}
<div class="rounded-3xl bg-emerald-50/70 border border-emerald-100 p-6 shadow-sm">

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-bold text-gray-900">
            Enrolled Students
        </h2>

        <span class="text-sm text-gray-500">
            Total: <span class="font-semibold text-gray-900">{{ $students->count() }}</span>
        </span>
    </div>

    <div class="overflow-x-auto rounded-2xl bg-white shadow-sm border border-gray-100">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Name</th>
                    <th class="px-6 py-4 text-left font-semibold">Email</th>
                    <th class="px-6 py-4 text-left font-semibold">Start Date</th>
                    <th class="px-6 py-4 text-left font-semibold">Result</th>
                    <th class="px-6 py-4 text-left font-semibold">Completion</th>
                    <th class="px-6 py-4 text-left font-semibold">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">
            @forelse($students as $student)
                <tr class="hover:bg-gray-50 transition">

                    {{-- NAME --}}
                    <td class="px-6 py-4 font-semibold text-gray-900">
                        {{ $student->name }}
                    </td>

                    {{-- EMAIL --}}
                    <td class="px-6 py-4 text-gray-700">
                        {{ $student->email }}
                    </td>

                    {{-- START DATE --}}
                    <td class="px-6 py-4">
                        {{ $student->pivot->enrolled_at
                            ? \Carbon\Carbon::parse($student->pivot->enrolled_at)->format('d M Y')
                            : '—'
                        }}
                    </td>

                    {{-- RESULT --}}
                    <td class="px-6 py-4">
                        @if($student->pivot->status === 'PASS')
                            <span class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                PASS
                            </span>
                        @elseif($student->pivot->status === 'FAIL')
                            <span class="inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">
                                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                FAIL
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 rounded-full bg-gray-200 px-3 py-1 text-xs font-semibold text-gray-700">
                                <span class="h-2 w-2 rounded-full bg-gray-500"></span>
                                Pending
                            </span>
                        @endif
                    </td>

                    {{-- COMPLETION --}}
                    <td class="px-6 py-4">
                        {{ $student->pivot->completed_at
                            ? \Carbon\Carbon::parse($student->pivot->completed_at)->format('d M Y')
                            : '—'
                        }}
                    </td>

                    {{-- ACTION --}}
                    <td class="px-6 py-4">
                        <form method="POST"
                              action="{{ route('admin.students.removeModule', [$student->id, $module->id]) }}"
                              onsubmit="return confirm('Remove student from this module?')">
                            @csrf
                            @method('DELETE')

                            <button
                                class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-50 transition">
                                Remove
                            </button>
                        </form>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center">
                        <p class="text-sm font-semibold text-gray-700">
                            No students enrolled
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Students will appear here once enrolled into this module.
                        </p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
