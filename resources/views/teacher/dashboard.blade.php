@extends('layouts.teacher')

@section('title', 'Teacher Dashboard')
@section('header', 'Dashboard')

@section('content')

<!-- ================= HERO SECTION ================= -->
<div class="mb-10">
    <div class="flex items-center justify-between
                px-8 py-12 rounded-2xl shadow-lg
                bg-gradient-to-r from-[#C7F000] via-[#8EE000] to-[#35B24A]
                text-white">

        <div>
            <h1 class="text-3xl font-bold">
                Welcome, {{ auth()->user()->name }} 👋
            </h1>
            <p class="mt-2 text-white/90">
                Manage your assigned modules and student results
            </p>
        </div>

        <div class="hidden md:flex items-center justify-center
                    w-14 h-14 rounded-full bg-white/20 text-2xl">
            🎓
        </div>
    </div>
</div>

<!-- ================= STATS ================= -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

    <!-- ASSIGNED MODULES -->
    <div class="rounded-2xl bg-white p-6 shadow border border-black/5">
        <div class="text-sm text-slate-500">Assigned Modules</div>
        <div class="mt-2 text-3xl font-bold text-slate-900">
            {{ auth()->user()->teachingModules()->count() }}
        </div>
    </div>

    <!-- ACTIVE STUDENTS -->
    <div class="rounded-2xl bg-white p-6 shadow border border-black/5">
        <div class="text-sm text-slate-500">Active Students</div>
        <div class="mt-2 text-3xl font-bold text-slate-900">
            {{
                auth()->user()->teachingModules()
                    ->withCount(['students as active_students_count' => function ($q) {
                        $q->whereNull('completed_at');
                    }])
                    ->get()
                    ->sum('active_students_count')
            }}
        </div>
    </div>

    <!-- SYSTEM STATUS -->
    <div class="rounded-2xl bg-white p-6 shadow border border-black/5">
        <div class="text-sm text-slate-500">System Status</div>
        <div class="mt-3 inline-flex items-center gap-2
                    px-3 py-1 rounded-full bg-green-100 text-green-700
                    text-sm font-semibold">
            Running ✅
        </div>
    </div>

</div>

<!-- ================= CHARTS ================= -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">

    <!-- STUDENT STATUS CHART -->
    <div class="bg-white rounded-2xl shadow border border-emerald-200 p-4">
        <h3 class="text-sm font-semibold text-slate-700 mb-4">
            Student Status
        </h3>
        <div class="h-32">
            <canvas id="studentStatusChart"></canvas>
        </div>
    </div>

    <!-- MODULES OVERVIEW CHART -->
    <div class="bg-white rounded-2xl shadow border border-emerald-200 p-4">
        <h3 class="text-sm font-semibold text-slate-700 mb-4">
            Students per Module
        </h3>
        <div class="h-32">
            <canvas id="modulesChart"></canvas>
        </div>
    </div>

</div>

<!-- ================= ACTION ================= -->
<div class="mt-4">
    <a href="{{ route('teacher.modules.index') }}"
       class="inline-flex items-center gap-3
              px-6 py-4 rounded-xl
              bg-slate-900 text-white
              hover:bg-slate-800 transition
              shadow-lg">
        📚 View My Modules
    </a>
</div>

<!-- ================= CHART.JS ================= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
/* -------- STUDENT STATUS -------- */
new Chart(document.getElementById('studentStatusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Active', 'Completed'],
        datasets: [{
            data: [
                {{ auth()->user()->teachingModules()
                        ->withCount(['students as active_students_count' => function ($q) {
                            $q->whereNull('completed_at');
                        }])->get()->sum('active_students_count') }},
                {{ auth()->user()->teachingModules()
                        ->withCount(['students as completed_students_count' => function ($q) {
                            $q->whereNotNull('completed_at');
                        }])->get()->sum('completed_students_count') }}
            ],
            backgroundColor: ['#22c55e', '#a3e635'],
            borderWidth: 0
        }]
    },
    options: {
        cutout: '70%',
        plugins: { legend: { position: 'bottom' } },
        maintainAspectRatio: false
    }
});

/* -------- MODULES OVERVIEW -------- */
new Chart(document.getElementById('modulesChart'), {
    type: 'bar',
    data: {
        labels: [
            @foreach(auth()->user()->teachingModules as $m)
                "{{ $m->name }}",
            @endforeach
        ],
        datasets: [{
            data: [
                @foreach(auth()->user()->teachingModules as $m)
                    {{ $m->students()->count() }},
                @endforeach
            ],
            backgroundColor: '#84cc16',
            borderRadius: 8
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 } }
        },
        maintainAspectRatio: false
    }
});
</script>

@endsection
