@extends('layouts.student')

@section('content')

<!-- match admin: max width + spacing -->
<div class="py-10">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- ================= WELCOME BANNER (EXACT ADMIN GRADIENT) ================= -->
        <div class="mb-10">
            <div class="flex items-center justify-between
                        px-8 py-12 rounded-2xl shadow-lg
                        bg-gradient-to-r from-[#E6F400] via-[#9CD400] to-[#3FA34D]
                        text-white">

                <div>
                    <h1 class="text-3xl font-bold">
                        Academic History 🎓
                    </h1>
                    <p class="mt-2 text-white/90">
                        Overview of your completed modules and final results
                    </p>
                </div>

                <div class="hidden md:flex items-center justify-center
                            w-12 h-12 rounded-full bg-white/20">
                    📘
                </div>
            </div>
        </div>

        @php
            $passed = $modules->where('pivot.status', 'PASS')->count();
            $failed = $modules->where('pivot.status', 'FAIL')->count();
            $total  = $modules->count();
        @endphp

        <!-- ================= TITLE (ADMIN STYLE) ================= -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900">
                Module Summary
            </h2>
            <p class="text-gray-500">
                Your academic performance overview
            </p>
        </div>

        <!-- ================= STATS GRID (ADMIN CARD STYLE) ================= -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">

            <div class="bg-indigo-100 rounded-2xl p-6 shadow">
                <p class="text-sm text-indigo-700">Modules Passed</p>
                <p class="text-4xl font-bold text-indigo-900 mt-2">{{ $passed }}</p>
            </div>

            <div class="bg-pink-100 rounded-2xl p-6 shadow">
                <p class="text-sm text-pink-700">Modules Failed</p>
                <p class="text-4xl font-bold text-pink-900 mt-2">{{ $failed }}</p>
            </div>

            <div class="bg-blue-100 rounded-2xl p-6 shadow">
                <p class="text-sm text-emerald-700">Total Completed</p>
                <p class="text-4xl font-bold text-emerald-900 mt-2">{{ $total }}</p>
            </div>

        </div>

        <!-- ================= COMPLETED MODULES LIST (ADMIN LONG BOX STYLE) ================= -->
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-gray-900">Completed Modules</h3>
            <p class="text-gray-500 mt-1">Final outcomes of your enrolled modules</p>
        </div>

        <div class="max-w-4xl space-y-4">

            @forelse($modules as $module)
                <div class="block bg-white rounded-xl shadow
                            border-2 border-green-400
                            px-8 py-6">

                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800">
                                {{ $module->name }}
                            </h4>

                            <p class="text-sm text-gray-500 mt-1">
                                Enrolled: {{ \Carbon\Carbon::parse($module->pivot->enrolled_at)->format('d M Y') }}
                                &nbsp;•&nbsp;
                                Completed: {{ \Carbon\Carbon::parse($module->pivot->completed_at)->format('d M Y') }}
                            </p>
                        </div>

                        <span class="px-4 py-2 rounded-full text-xs font-bold
                            {{ $module->pivot->status === 'PASS'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700' }}">
                            {{ $module->pivot->status }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="bg-white p-6 rounded-xl shadow text-gray-600">
                    You have not completed any modules yet.
                </div>
            @endforelse

        </div>

    </div>
</div>

@endsection
