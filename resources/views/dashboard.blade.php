@extends('layouts.admin')

@section('content')

{{-- ================= TOP GRADIENT STRIP ================= --}}
<div class="mb-10 rounded-3xl bg-gradient-to-r from-lime-400 via-green-400 to-emerald-500 p-10 shadow-lg">
    <h1 class="text-3xl font-bold text-white">
        Welcome to Admin Dashboard 👋
    </h1>
    <p class="mt-2 text-white/90">
        Here’s what’s happening with your system today
    </p>
</div>

{{-- ================= DASHBOARD TITLE ================= --}}
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Admin Dashboard</h2>
    <p class="text-gray-600">System overview & management controls</p>
</div>

{{-- ================= STATS CARDS ================= --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

    <div class="rounded-2xl bg-blue-100 p-6 shadow">
        <p class="text-sm text-blue-700 font-semibold">Total Students</p>
        <p class="text-3xl font-bold text-blue-900">{{ $totalStudents ?? 0 }}</p>
    </div>

    <div class="rounded-2xl bg-indigo-100 p-6 shadow">
        <p class="text-sm text-indigo-700 font-semibold">Current Students</p>
        <p class="text-3xl font-bold text-indigo-900">{{ $currentStudents ?? 0 }}</p>
    </div>

    <div class="rounded-2xl bg-purple-100 p-6 shadow">
        <p class="text-sm text-purple-700 font-semibold">Old Students</p>
        <p class="text-3xl font-bold text-purple-900">{{ $oldStudents ?? 0 }}</p>
    </div>

    <div class="rounded-2xl bg-pink-100 p-6 shadow">
        <p class="text-sm text-pink-700 font-semibold">Total Teachers</p>
        <p class="text-3xl font-bold text-pink-900">{{ $totalTeachers ?? 0 }}</p>
    </div>

    <div class="rounded-2xl bg-rose-100 p-6 shadow">
        <p class="text-sm text-rose-700 font-semibold">Total Modules</p>
        <p class="text-3xl font-bold text-rose-900">{{ $totalModules ?? 0 }}</p>
    </div>

    <div class="rounded-2xl bg-emerald-100 p-6 shadow flex items-center justify-between">
        <div>
            <p class="text-sm text-emerald-700 font-semibold">System Status</p>
            <p class="text-xl font-bold text-emerald-900">Running</p>
        </div>
        <span class="text-2xl">✅</span>
    </div>

</div>

{{-- ================= QUICK ACTIONS ================= --}}
<div class="rounded-2xl bg-white p-8 shadow">
    <h3 class="text-lg font-bold text-gray-900 mb-4">
        Quick Actions
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <a href="{{ route('admin.students.index') }}"
           class="rounded-xl border border-emerald-200 p-5 hover:bg-emerald-50 transition">
            <h4 class="font-semibold text-emerald-800">Students</h4>
            <p class="text-sm text-gray-600">View & manage students</p>
        </a>

        <a href="{{ route('admin.modules.index') }}"
           class="rounded-xl border border-indigo-200 p-5 hover:bg-indigo-50 transition">
            <h4 class="font-semibold text-indigo-800">Modules</h4>
            <p class="text-sm text-gray-600">Manage academic modules</p>
        </a>

        <a href="{{ route('admin.teachers.index') }}"
           class="rounded-xl border border-pink-200 p-5 hover:bg-pink-50 transition">
            <h4 class="font-semibold text-pink-800">Teachers</h4>
            <p class="text-sm text-gray-600">Assign & manage teachers</p>
        </a>

    </div>
</div>

@endsection
