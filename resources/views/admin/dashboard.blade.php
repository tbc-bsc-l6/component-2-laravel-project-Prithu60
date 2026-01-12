@extends('layouts.admin')

@section('content')

<!-- ================= SMOOTH GREEN -> DARK GRADIENT HEADER ================= -->
<div class="relative mb-12 overflow-hidden rounded-3xl shadow-lg">
    <div class="relative h-48">

        <!-- Base gradient (green to dark/black) -->
        <div class="absolute inset-0 bg-[linear-gradient(110deg,#00b84a_0%,#0b7a46_35%,#064a33_60%,#0a0a0a_100%)]"></div>

        <!-- Soft glow (bottom-left) -->
        <div class="absolute -left-28 -bottom-28 h-[420px] w-[420px]
                    bg-[radial-gradient(circle_at_center,rgba(0,255,140,0.55)_0%,rgba(0,255,140,0)_60%)]
                    blur-3xl"></div>

        <!-- Extra soft glow (upper-left) -->
        <div class="absolute left-24 top-6 h-[320px] w-[320px]
                    bg-[radial-gradient(circle_at_center,rgba(0,200,90,0.30)_0%,rgba(0,200,90,0)_60%)]
                    blur-3xl"></div>

        <!-- Slight dark overlay for depth -->
        <div class="absolute inset-0 bg-black/10"></div>

        <!-- Content -->
        <div class="relative z-10 flex h-full items-center justify-between px-12">
            <div>
                <h1 class="text-4xl font-bold text-white">
                    Welcome to Admin Dashboard 👋
                </h1>
                <p class="mt-2 text-white/85 text-lg">
                    Here’s what’s happening with your system today
                </p>
            </div>

            <div class="hidden md:flex items-center justify-center
                        w-12 h-12 rounded-full bg-white/15 text-white">
                🎓
            </div>
        </div>
    </div>
</div>

<!-- ================= TITLE ================= -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900">Admin Dashboard</h2>
    <p class="text-gray-500">Overview & management controls</p>
</div>

<!-- ================= STATS GRID ================= -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-14">

    <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-6 shadow-sm">
        <p class="text-sm text-indigo-700">Total Students</p>
        <p class="text-4xl font-bold text-indigo-900 mt-2">{{ $totalStudents }}</p>
    </div>

    <div class="bg-sky-50 border border-sky-200 rounded-2xl p-6 shadow-sm">
        <p class="text-sm text-sky-700">Current Students</p>
        <p class="text-4xl font-bold text-sky-900 mt-2">{{ $currentStudents }}</p>
    </div>

    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 shadow-sm">
        <p class="text-sm text-emerald-700">Old Students</p>
        <p class="text-4xl font-bold text-emerald-900 mt-2">{{ $oldStudents }}</p>
    </div>

    <div class="bg-violet-50 border border-violet-200 rounded-2xl p-6 shadow-sm">
        <p class="text-sm text-violet-700">Teachers</p>
        <p class="text-4xl font-bold text-violet-900 mt-2">
            {{ \App\Models\User::whereHas('role', fn($q) => $q->where('role','teacher'))->count() }}
        </p>
    </div>

    <div class="bg-pink-50 border border-pink-200 rounded-2xl p-6 shadow-sm">
        <p class="text-sm text-pink-700">Modules</p>
        <p class="text-4xl font-bold text-pink-900 mt-2">
            {{ \App\Models\Module::count() }}
        </p>
    </div>

    <div class="bg-green-50 border border-green-200 rounded-2xl p-6 shadow-sm">
        <p class="text-sm text-green-700">System Status</p>
        <p class="text-2xl font-bold text-green-900 mt-4">
            Running ✓
        </p>
    </div>

</div>

<!-- ================= QUICK ACTIONS ================= -->
<div class="max-w-4xl space-y-4">

    <a href="{{ route('admin.students.index') }}"
       class="block bg-white rounded-xl shadow
              border-2 border-emerald-500
              px-8 py-6
              hover:bg-emerald-50 transition">
        <h3 class="text-lg font-semibold text-gray-800">Students</h3>
        <p class="text-sm text-gray-500 mt-1">View & manage current students</p>
    </a>

    <a href="{{ route('admin.old-students.index') }}"
       class="block bg-white rounded-xl shadow
              border-2 border-emerald-500
              px-8 py-6
              hover:bg-emerald-50 transition">
        <h3 class="text-lg font-semibold text-gray-800">Old Students</h3>
        <p class="text-sm text-gray-500 mt-1">Completed students & history</p>
    </a>

    <a href="{{ route('admin.teachers.index') }}"
       class="block bg-white rounded-xl shadow
              border-2 border-emerald-500
              px-8 py-6
              hover:bg-emerald-50 transition">
        <h3 class="text-lg font-semibold text-gray-800">Teachers</h3>
        <p class="text-sm text-gray-500 mt-1">Create & manage teachers</p>
    </a>

    <a href="{{ route('admin.modules.index') }}"
       class="block bg-white rounded-xl shadow
              border-2 border-emerald-500
              px-8 py-6
              hover:bg-emerald-50 transition">
        <h3 class="text-lg font-semibold text-gray-800">Modules</h3>
        <p class="text-sm text-gray-500 mt-1">Create & manage modules</p>
    </a>

</div>

@endsection
