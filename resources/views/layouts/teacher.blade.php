<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Teacher Panel')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F6F2EA] text-slate-900 antialiased">

<div class="min-h-screen flex">

    <!-- ================= SIDEBAR ================= -->
    <aside class="w-64 shrink-0 bg-gradient-to-b from-[#070B18] to-[#0B1635] text-white flex flex-col">

        <!-- LOGO -->
        <div class="px-6 py-6 flex items-center gap-3 border-b border-white/10">
            <div class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center font-bold">
                SM
            </div>
            <div>
                <div class="font-semibold leading-tight">Edu World</div>
                <div class="text-xs text-white/60">Teacher Panel</div>
            </div>
        </div>

        <!-- NAV -->
        <nav class="px-4 py-6 space-y-2 flex-1">

            <a href="{{ route('teacher.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
               {{ request()->routeIs('teacher.dashboard') ? 'bg-white/10' : 'hover:bg-white/10' }}">
                🏠 Dashboard
            </a>

            <a href="{{ route('teacher.modules.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
               {{ request()->routeIs('teacher.modules.*') ? 'bg-white/10' : 'hover:bg-white/10' }}">
                📚 My Modules
            </a>

            <a href="{{ route('teacher.students.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
               {{ request()->routeIs('teacher.students.index') ? 'bg-white/10' : 'hover:bg-white/10' }}">
                👥 My Students
            </a>

            <a href="{{ route('teacher.students.old') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
               {{ request()->routeIs('teacher.students.old') ? 'bg-white/10' : 'hover:bg-white/10' }}">
                🎓 Old Students
            </a>

        </nav>

        <!-- LOGOUT -->
        <div class="px-4 pb-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    class="w-full rounded-xl bg-white/10 px-4 py-3 text-sm
                           hover:bg-white/20 transition">
                    Logout
                </button>
            </form>
        </div>

    </aside>

    <!-- ================= MAIN ================= -->
    <div class="flex-1 flex flex-col">

        <!-- HEADER -->
        <header class="h-16 flex items-center justify-between px-8
                       bg-white/70 backdrop-blur
                       border-b border-black/5">

            <h1 class="font-semibold text-slate-800">
                @yield('header', 'Teacher Dashboard')
            </h1>

            <div class="text-sm text-slate-700 flex items-center gap-2">
                👤 {{ auth()->user()->name }}
            </div>
        </header>

        <!-- CONTENT -->
        <main class="flex-1 px-8 py-8">
            @yield('content')
        </main>

    </div>

</div>

</body>
</html>
