@extends('layouts.admin')

@section('title', 'Old Students')
@section('header', 'Old Students')

@section('content')

{{-- =====================
| PAGE HEADER
===================== --}}
<div class="mb-10">
    <div class="rounded-3xl bg-gradient-to-r
                from-[#E6F400] via-[#9CD400] to-[#3FA34D]
                p-10 shadow-lg text-white">

        <h1 class="text-3xl font-bold">
            🎓 Old Students
        </h1>

        <p class="mt-2 text-white/90">
            Students who have completed all assigned modules
        </p>
    </div>
</div>

{{-- =====================
| OLD STUDENTS TABLE
===================== --}}
<div class="bg-white rounded-3xl shadow-lg p-8">

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="p-4 text-left">Name</th>
                    <th class="p-4 text-left">Email</th>
                    <th class="p-4 text-left">Status</th>
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

                        {{-- STATUS --}}
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                         bg-purple-100 text-purple-700">
                                Old Student
                            </span>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-6 text-center text-slate-500">
                            No old students found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
