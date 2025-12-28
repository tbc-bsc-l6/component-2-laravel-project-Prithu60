@extends('layouts.teacher')

@section('title', 'Module Students')
@section('header', 'Module Students')

@section('content')

<!-- ================= MODULE INFO ================= -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-900">
        {{ $module->name }}
    </h2>
    <p class="text-slate-600">
        Manage student results for this module
    </p>
</div>

<!-- ================= STUDENTS TABLE ================= -->
@if($students->count())
    <div class="bg-white rounded-2xl shadow border border-black/5 overflow-hidden">

        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-slate-700">
                <tr>
                    <th class="p-4 text-left">Name</th>
                    <th class="p-4 text-left">Email</th>
                    <th class="p-4 text-left">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($students as $student)
                    <tr class="border-t">
                        <td class="p-4 font-medium">
                            {{ $student->name }}
                        </td>
                        <td class="p-4">
                            {{ $student->email }}
                        </td>
                        <td class="p-4">
                            <div class="flex gap-2">

                                <!-- PASS -->
                                <form method="POST"
                                      action="{{ route('teacher.modules.students.pass', [$module, $student]) }}">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-1 rounded-lg
                                                   bg-green-600 text-white
                                                   hover:bg-green-700 text-xs">
                                        PASS
                                    </button>
                                </form>

                                <!-- FAIL -->
                                <form method="POST"
                                      action="{{ route('teacher.modules.students.fail', [$module, $student]) }}">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-1 rounded-lg
                                                   bg-red-600 text-white
                                                   hover:bg-red-700 text-xs">
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
@else
    <div class="rounded-xl bg-white p-6 text-slate-600 shadow">
        No active students enrolled in this module.
    </div>
@endif

<!-- ================= BACK ================= -->
<div class="mt-8">
    <a href="{{ route('teacher.modules.index') }}"
       class="inline-flex items-center gap-2
              px-4 py-2 rounded-lg
              bg-slate-200 text-slate-800
              hover:bg-slate-300 transition text-sm">
        ← Back to Modules
    </a>
</div>

@endsection
