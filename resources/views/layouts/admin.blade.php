<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SM Info Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FAF7F0]">

<div class="flex min-h-screen">

    <!-- ================= SIDEBAR ================= -->
    <aside
        class="w-64 shrink-0
               bg-gradient-to-b from-[#0F172A] via-[#0B1220] to-[#020617]
               text-white shadow-2xl flex flex-col">

        <!-- LOGO -->
        <div class="px-6 py-5 text-xl font-bold border-b border-white/10">
            📊 Edu World
        </div>

        <!-- NAV -->
        <nav class="flex-1 px-4 py-4 space-y-2 text-[15px] font-medium">

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition
               {{ request()->routeIs('admin.dashboard')
                    ? 'bg-white text-gray-900'
                    : 'hover:bg-white/10 text-white' }}">
                🏠 Dashboard
            </a>

            <a href="{{ route('admin.modules.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition
               {{ request()->routeIs('admin.modules.*')
                    ? 'bg-white text-gray-900'
                    : 'hover:bg-white/10 text-white' }}">
                📚 Modules
            </a>

            <a href="{{ route('admin.teachers.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition
               {{ request()->routeIs('admin.teachers.*')
                    ? 'bg-white text-gray-900'
                    : 'hover:bg-white/10 text-white' }}">
                👨‍🏫 Teachers
            </a>

            <a href="{{ route('admin.students.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition
               {{ request()->routeIs('admin.students.*')
                    ? 'bg-white text-gray-900'
                    : 'hover:bg-white/10 text-white' }}">
                🎓 Students
            </a>

            <!-- ✅ OLD STUDENTS (NEW) -->
            <a href="{{ route('admin.old-students.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition
               {{ request()->routeIs('admin.old-students.*')
                    ? 'bg-white text-gray-900'
                    : 'hover:bg-white/10 text-white' }}">
                🎓 Old Students
            </a>

        </nav>

        <!-- LOGOUT -->
        <div class="px-4 py-4 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl
                           text-red-400 hover:bg-red-500/10 transition">
                    🚪 Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 bg-[#FAF7F0]">
        <div class="max-w-7xl mx-auto px-10 py-6">
            @yield('content')
        </div>
    </main>

</div>

</body>
</html>
