<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white shadow-md">
        <div class="p-6 text-xl font-bold text-indigo-600">
            🎓 SM Info
        </div>

        <nav class="px-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}"
               class="block px-4 py-2 rounded hover:bg-indigo-100">
                Dashboard
            </a>

            <a href="{{ route('admin.modules.index') }}"
               class="block px-4 py-2 rounded hover:bg-indigo-100">
                Modules
            </a>

            <a href="{{ route('admin.teachers.index') }}"
               class="block px-4 py-2 rounded hover:bg-indigo-100">
                Teachers
            </a>

            <a href="{{ route('admin.students.index') }}"
               class="block px-4 py-2 rounded hover:bg-indigo-100">
                Students
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-8">
        @yield('content')
    </main>

</div>

</body>
</html>
