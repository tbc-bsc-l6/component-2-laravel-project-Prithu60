@extends('layouts.admin')

@section('content')

<style>
    /* --- Simple “cute” slide-in animations (no libraries) --- */
    .slide-up {
        opacity: 0;
        transform: translateY(14px);
        transition: all .55s ease;
    }
    .slide-up.show {
        opacity: 1;
        transform: translateY(0);
    }
    .slide-left {
        opacity: 0;
        transform: translateX(-16px);
        transition: all .55s ease;
    }
    .slide-left.show {
        opacity: 1;
        transform: translateX(0);
    }
</style>

<!-- ================= SMOOTH GREEN -> DARK GRADIENT HEADER (slightly smaller) ================= -->
<div class="relative mb-8 overflow-hidden rounded-3xl shadow-lg slide-left" data-anim>
    <div class="relative h-36 md:h-40">

        <!-- Base gradient -->
        <div class="absolute inset-0 bg-[linear-gradient(110deg,#00b84a_0%,#0b7a46_35%,#064a33_60%,#0a0a0a_100%)]"></div>

        <!-- Soft glows -->
        <div class="absolute -left-24 -bottom-24 h-[320px] w-[320px]
                    bg-[radial-gradient(circle_at_center,rgba(0,255,140,0.45)_0%,rgba(0,255,140,0)_60%)]
                    blur-3xl"></div>

        <div class="absolute left-14 top-4 h-[260px] w-[260px]
                    bg-[radial-gradient(circle_at_center,rgba(0,200,90,0.22)_0%,rgba(0,200,90,0)_60%)]
                    blur-3xl"></div>

        <div class="absolute inset-0 bg-black/10"></div>

        <!-- Content -->
        <div class="relative z-10 flex h-full items-center justify-between px-8 md:px-10">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white leading-tight">
                    Welcome to Admin Dashboard 👋
                </h1>
                <p class="mt-1 text-white/85 text-sm md:text-base">
                    Here’s what’s happening with your system today
                </p>
            </div>

            <div class="hidden md:flex items-center justify-center
                        w-10 h-10 rounded-full bg-white/15 text-white">
                🎓
            </div>
        </div>
    </div>
</div>

<!-- ================= TITLE ================= -->
<div class="mb-5 slide-up" data-anim>
    <h2 class="text-xl md:text-2xl font-bold text-gray-900">Admin Dashboard</h2>
    <p class="text-gray-500 text-sm">Overview & management controls</p>
</div>

<!-- ================= SMALL + CUTE STATS GRID ================= -->
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8 slide-up" data-anim>

    {{-- Small stat card component style --}}
    @php
        $statCard = "rounded-2xl p-4 shadow-sm border";
        $statLabel = "text-xs font-medium";
        $statValue = "text-2xl md:text-3xl font-bold mt-1";
    @endphp

    <div class="{{ $statCard }} bg-indigo-50 border-indigo-200">
        <p class="{{ $statLabel }} text-indigo-700">Total Students</p>
        <p class="{{ $statValue }} text-indigo-900">{{ $totalStudents }}</p>
    </div>

    <div class="{{ $statCard }} bg-sky-50 border-sky-200">
        <p class="{{ $statLabel }} text-sky-700">Current Students</p>
        <p class="{{ $statValue }} text-sky-900">{{ $currentStudents }}</p>
    </div>

    <div class="{{ $statCard }} bg-emerald-50 border-emerald-200">
        <p class="{{ $statLabel }} text-emerald-700">Old Students</p>
        <p class="{{ $statValue }} text-emerald-900">{{ $oldStudents }}</p>
    </div>

    <div class="{{ $statCard }} bg-violet-50 border-violet-200">
        <p class="{{ $statLabel }} text-violet-700">Teachers</p>
        <p class="{{ $statValue }} text-violet-900">{{ $totalTeachers }}</p>
    </div>

    <div class="{{ $statCard }} bg-pink-50 border-pink-200">
        <p class="{{ $statLabel }} text-pink-700">Modules</p>
        <p class="{{ $statValue }} text-pink-900">{{ $totalModules }}</p>
    </div>

    <div class="{{ $statCard }} bg-green-50 border-green-200 flex items-center justify-between">
        <div>
            <p class="{{ $statLabel }} text-green-700">System Status</p>
            <p class="text-lg md:text-xl font-bold text-green-900 mt-1">Running ✓</p>
        </div>
        <div class="h-9 w-9 rounded-xl bg-green-100 border border-green-200 flex items-center justify-center">
            ✅
        </div>
    </div>
</div>

<!-- ================= SMALLER CHARTS (cute cards) ================= -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">

    {{-- Chart 1 --}}
    <div class="rounded-2xl bg-white border border-gray-100 p-4 shadow-sm slide-up" data-anim>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm md:text-base font-semibold text-gray-800">System Overview</h3>
            <span class="text-[11px] text-gray-500">Counts</span>
        </div>

        <div class="rounded-2xl bg-gradient-to-br from-emerald-50 to-white border border-emerald-100 p-3">
            <canvas id="overviewChart" height="95"></canvas>
        </div>
    </div>

    {{-- Chart 2 --}}
    <div class="rounded-2xl bg-white border border-gray-100 p-4 shadow-sm slide-up" data-anim>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm md:text-base font-semibold text-gray-800">Role Distribution</h3>
            <span class="text-[11px] text-gray-500">Users</span>
        </div>

        <div class="rounded-2xl bg-gradient-to-br from-emerald-50 to-white border border-emerald-100 p-3">
            <div class="max-w-[320px] mx-auto">
                <canvas id="roleChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- ================= QUICK ACTIONS (more compact + cute) ================= -->
<div class="max-w-4xl space-y-3 slide-up" data-anim>

    <a href="{{ route('admin.students.index') }}"
       class="block bg-white rounded-2xl shadow-sm
              border border-emerald-200
              px-5 py-4
              hover:bg-emerald-50 transition">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm md:text-base font-semibold text-gray-800">Students</h3>
                <p class="text-xs text-gray-500 mt-0.5">View & manage current students</p>
            </div>
            <span class="text-xs text-emerald-700 font-semibold">Open →</span>
        </div>
    </a>

    <a href="{{ route('admin.old-students.index') }}"
       class="block bg-white rounded-2xl shadow-sm
              border border-emerald-200
              px-5 py-4
              hover:bg-emerald-50 transition">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm md:text-base font-semibold text-gray-800">Old Students</h3>
                <p class="text-xs text-gray-500 mt-0.5">Completed students & history</p>
            </div>
            <span class="text-xs text-emerald-700 font-semibold">Open →</span>
        </div>
    </a>

    <a href="{{ route('admin.teachers.index') }}"
       class="block bg-white rounded-2xl shadow-sm
              border border-emerald-200
              px-5 py-4
              hover:bg-emerald-50 transition">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm md:text-base font-semibold text-gray-800">Teachers</h3>
                <p class="text-xs text-gray-500 mt-0.5">Create & manage teachers</p>
            </div>
            <span class="text-xs text-emerald-700 font-semibold">Open →</span>
        </div>
    </a>

    <a href="{{ route('admin.modules.index') }}"
       class="block bg-white rounded-2xl shadow-sm
              border border-emerald-200
              px-5 py-4
              hover:bg-emerald-50 transition">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm md:text-base font-semibold text-gray-800">Modules</h3>
                <p class="text-xs text-gray-500 mt-0.5">Create & manage modules</p>
            </div>
            <span class="text-xs text-emerald-700 font-semibold">Open →</span>
        </div>
    </a>

</div>

{{-- ================= CHART.JS (LOAD ONCE) ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ----- Slide-in reveal -----
    const items = document.querySelectorAll('[data-anim]');
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                // pick a class based on initial style desire
                if (e.target.classList.contains('slide-left')) e.target.classList.add('show');
                else e.target.classList.add('show');
                obs.unobserve(e.target);
            }
        });
    }, { threshold: 0.12 });

    items.forEach(el => {
        // apply base class if not already
        if (!el.classList.contains('slide-up') && !el.classList.contains('slide-left')) {
            el.classList.add('slide-up');
        }
        obs.observe(el);
    });

    // ----- Chart 1: compact gradient bar -----
    const overviewCanvas = document.getElementById('overviewChart');
    const octx = overviewCanvas.getContext('2d');

    const overviewGradient = octx.createLinearGradient(0, 0, 0, 200);
    overviewGradient.addColorStop(0, 'rgba(16, 185, 129, 0.95)');
    overviewGradient.addColorStop(1, 'rgba(10, 10, 10, 0.25)');

    new Chart(overviewCanvas, {
        type: 'bar',
        data: {
            labels: ['Students', 'Old', 'Teachers', 'Modules'],
            datasets: [{
                data: [
                    {{ $currentStudents }},
                    {{ $oldStudents }},
                    {{ $totalTeachers }},
                    {{ $totalModules }}
                ],
                backgroundColor: overviewGradient,
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 1,
                borderRadius: 12,
                barThickness: 26
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(10,10,10,0.9)',
                    padding: 10,
                    cornerRadius: 10
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#374151', font: { size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(229,231,235,0.85)' },
                    ticks: { precision: 0, color: '#374151', font: { size: 11 } }
                }
            }
        }
    });

    // ----- Chart 2: compact doughnut -----
    const roleCanvas = document.getElementById('roleChart');

    new Chart(roleCanvas, {
        type: 'doughnut',
        data: {
            labels: ['Students', 'Old Students', 'Teachers'],
            datasets: [{
                data: [
                    {{ $currentStudents }},
                    {{ $oldStudents }},
                    {{ $totalTeachers }}
                ],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.90)',
                    'rgba(168, 85, 247, 0.90)',
                    'rgba(34, 197, 94, 0.90)'
                ],
                borderColor: 'rgba(255,255,255,0.95)',
                borderWidth: 3,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 14,
                        font: { size: 11 }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(10,10,10,0.9)',
                    padding: 10,
                    cornerRadius: 10
                }
            }
        }
    });
</script>

@endsection
