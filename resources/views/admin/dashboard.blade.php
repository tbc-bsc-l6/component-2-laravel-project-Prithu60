@extends('layouts.admin')

@section('content')
<!-- WELCOME BANNER -->
<div class="relative mb-10">
    <div class="flex items-center justify-between p-8 rounded-2xl shadow-lg
                bg-gradient-to-r from-fuchsia-600 via-purple-600 to-pink-600
                text-white">

        <!-- Text -->
        <div>
            <h1 class="text-3xl font-bold">
                Welcome to Admin Dashboard 👋
            </h1>
            <p class="mt-2 text-white/90">
                Here’s what’s happening with your system today
            </p>
        </div>

        <!-- Avatar / Icon -->
        <div class="hidden md:flex items-center justify-center
                    w-16 h-16 rounded-full bg-white/20 text-3xl">
            🎓
        </div>
    </div>
</div>


<!-- PAGE TITLE -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
    <p class="text-gray-500">Overview & management controls</p>
</div>

<!-- COLORED STAT CARDS -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

    <!-- Students -->
    <div class="flex items-center justify-between p-6 rounded-xl shadow-lg bg-gradient-to-r from-pink-500 to-rose-500 text-white">
        <div>
            <p class="text-sm opacity-90">Students</p>
            <h2 class="text-4xl font-bold">120</h2>
        </div>
        <div class="text-4xl opacity-80">🎓</div>
    </div>

    <!-- Teachers -->
    <div class="flex items-center justify-between p-6 rounded-xl shadow-lg bg-gradient-to-r from-indigo-500 to-purple-500 text-white">
        <div>
            <p class="text-sm opacity-90">Teachers</p>
            <h2 class="text-4xl font-bold">8</h2>
        </div>
        <div class="text-4xl opacity-80">👨‍🏫</div>
    </div>

    <!-- Modules -->
    <div class="flex items-center justify-between p-6 rounded-xl shadow-lg bg-gradient-to-r from-cyan-500 to-blue-500 text-white">
        <div>
            <p class="text-sm opacity-90">Modules</p>
            <h2 class="text-4xl font-bold">12</h2>
        </div>
        <div class="text-4xl opacity-80">📘</div>
    </div>

    <!-- Active Modules -->
    <div class="flex items-center justify-between p-6 rounded-xl shadow-lg bg-gradient-to-r from-emerald-500 to-green-500 text-white">
        <div>
            <p class="text-sm opacity-90">Active Modules</p>
            <h2 class="text-4xl font-bold">9</h2>
        </div>
        <div class="text-4xl opacity-80">✅</div>
    </div>

    <!-- Enrollments -->
    <div class="flex items-center justify-between p-6 rounded-xl shadow-lg bg-gradient-to-r from-yellow-500 to-orange-500 text-white">
        <div>
            <p class="text-sm opacity-90">Enrollments</p>
            <h2 class="text-4xl font-bold">34</h2>
        </div>
        <div class="text-4xl opacity-80">📝</div>
    </div>

    <!-- System Status -->
    <div class="flex items-center justify-between p-6 rounded-xl shadow-lg bg-gradient-to-r from-slate-700 to-slate-900 text-white">
        <div>
            <p class="text-sm opacity-90">System Status</p>
            <h2 class="text-xl font-semibold mt-2">Running</h2>
        </div>
        <div class="text-4xl opacity-80">⚙️</div>
    </div>

</div>

<!-- LOWER SECTION -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- QUICK ACTIONS -->
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Quick Actions</h2>

        <div class="space-y-3">
            <a href="{{ route('admin.modules.index') }}" class="flex items-center gap-3 p-3 rounded-lg border hover:bg-gray-100">
                ➕ Create New Module
            </a>

            <a href="{{ route('admin.teachers.index') }}" class="flex items-center gap-3 p-3 rounded-lg border hover:bg-gray-100">
                👨‍🏫 Manage Teachers
            </a>

            <a href="{{ route('admin.students.index') }}" class="flex items-center gap-3 p-3 rounded-lg border hover:bg-gray-100">
                🎓 View Students
            </a>
        </div>
    </div>

    <!-- RECENT ACTIVITY -->
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Recent Activity</h2>

        <ul class="space-y-3 text-sm text-gray-600">
            <li>• New module <b>Web Development</b> created</li>
            <li>• Teacher <b>John Doe</b> assigned</li>
            <li>• Student <b>Anna Smith</b> enrolled</li>
        </ul>
    </div>

</div>

@endsection
