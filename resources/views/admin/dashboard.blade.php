@extends('layouts.admin')

@section('content')

<!-- ================= WELCOME BANNER ================= -->
<div class="mb-10">
    <div class="flex items-center justify-between
                px-8 py-12 rounded-2xl shadow-lg
                bg-gradient-to-r from-[#E6F400] via-[#9CD400] to-[#3FA34D]
                text-white">

        <div>
            <h1 class="text-3xl font-bold">
                Welcome to Admin Dashboard 👋
            </h1>
            <p class="mt-2 text-white/90">
                Here’s what’s happening with your system today
            </p>
        </div>

        <div class="hidden md:flex items-center justify-center
                    w-12 h-12 rounded-full bg-white/20">
            🎓
        </div>
    </div>
</div>

<!-- ================= TITLE ================= -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900">
        Admin Dashboard
    </h2>
    <p class="text-gray-500">
        System overview & management controls
    </p>
</div>

<!-- ================= STATS GRID ================= -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">

    <div class="bg-indigo-100 rounded-2xl p-6 shadow">
        <p class="text-sm text-indigo-700">Total Students</p>
        <p class="text-4xl font-bold text-indigo-900 mt-2">{{ $totalStudents }}</p>
    </div>

    <div class="bg-blue-100 rounded-2xl p-6 shadow">
        <p class="text-sm text-blue-700">Current Students</p>
        <p class="text-4xl font-bold text-blue-900 mt-2">{{ $currentStudents }}</p>
    </div>

    <div class="bg-green-100 rounded-2xl p-6 shadow">
        <p class="text-sm text-green-700">Old Students</p>
        <p class="text-4xl font-bold text-green-900 mt-2">{{ $oldStudents }}</p>
    </div>

    <div class="bg-purple-100 rounded-2xl p-6 shadow">
        <p class="text-sm text-purple-700">Total Teachers</p>
        <p class="text-4xl font-bold text-purple-900 mt-2">
            {{ \App\Models\User::whereHas('role', fn($q) => $q->where('role','teacher'))->count() }}
        </p>
    </div>

    <div class="bg-pink-100 rounded-2xl p-6 shadow">
        <p class="text-sm text-pink-700">Total Modules</p>
        <p class="text-4xl font-bold text-pink-900 mt-2">
            {{ \App\Models\Module::count() }}
        </p>
    </div>

    <div class="bg-emerald-100 rounded-2xl p-6 shadow">
        <p class="text-sm text-emerald-700">System Status</p>
        <p class="text-2xl font-bold text-emerald-900 mt-4">
            Running ✅
        </p>
    </div>

</div>

<!-- ================= LONG ACTION BOXES (FIXED WIDTH) ================= -->
<div class="max-w-4xl space-y-4">

    <a href="{{ route('admin.students.index') }}"
       class="block bg-white rounded-xl shadow
              border-2 border-green-300
              px-8 py-6
              hover:bg-green-50 transition">
        <h3 class="text-lg font-semibold text-gray-800">Students</h3>
        <p class="text-sm text-gray-500 mt-1">
            View & manage current students
        </p>
    </a>

    <a href="{{ route('admin.old-students.index') }}"
       class="block bg-white rounded-xl shadow
              border-2 border-green-300
              px-8 py-6
              hover:bg-green-50 transition">
        <h3 class="text-lg font-semibold text-gray-800">Old Students</h3>
        <p class="text-sm text-gray-500 mt-1">
            Completed students & history
        </p>
    </a>

    <a href="{{ route('admin.teachers.index') }}"
       class="block bg-white rounded-xl shadow
              border-2 border-green-300
              px-8 py-6
              hover:bg-green-50 transition">
        <h3 class="text-lg font-semibold text-gray-800">Teachers</h3>
        <p class="text-sm text-gray-500 mt-1">
            Create & manage teachers
        </p>
    </a>

    <a href="{{ route('admin.modules.index') }}"
       class="block bg-white rounded-xl shadow
              border-2 border-green-300
              px-8 py-6
              hover:bg-green-50 transition">
        <h3 class="text-lg font-semibold text-gray-800">Modules</h3>
        <p class="text-sm text-gray-500 mt-1">
            Create & manage modules
        </p>
    </a>

</div>

@endsection
