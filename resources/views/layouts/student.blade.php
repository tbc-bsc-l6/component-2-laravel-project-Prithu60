<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Student Dashboard')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

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
            🎓 Student Panel
        </div>

        <!-- USER INFO -->
        <div class="px-6 py-4 border-b border-white/10">
            <p class="text-xs text-white/60">Logged in as</p>
            <p class="font-semibold">
                {{ auth()->user()->name }}
            </p>
        </div>

        @php
            $role = optional(auth()->user()->role)->role;
        @endphp

        <!-- NAV -->
        <nav class="flex-1 px-4 py-4 space-y-2 text-[15px] font-medium">

            {{-- ================= CURRENT STUDENT ONLY ================= --}}
            @if($role === 'student')

                <!-- DASHBOARD -->
                <a href="{{ route('student.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('student.dashboard')
                        ? 'bg-white text-gray-900'
                        : 'hover:bg-white/10 text-white' }}">
                    🏠 Dashboard
                </a>

                <!-- MODULES -->
                <a href="{{ route('student.modules.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('student.modules.*')
                        ? 'bg-white text-gray-900'
                        : 'hover:bg-white/10 text-white' }}">
                    📚 Modules
                </a>

            @endif

            {{-- ================= OLD STUDENT ONLY ================= --}}
            @if($role === 'old_student')

                <!-- COMPLETED MODULES -->
                <a href="{{ route('student.modules.completed') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('student.modules.completed')
                        ? 'bg-white text-gray-900'
                        : 'hover:bg-white/10 text-white' }}">
                    🎓 Completed Modules
                </a>

            @endif

        </nav>

        <!-- LOGOUT -->
        <div class="px-4 py-4 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    onclick="return confirm('Are you sure you want to logout?')"
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
