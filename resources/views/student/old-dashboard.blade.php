@extends('layouts.student')

@section('content')

<h1 class="text-2xl font-bold mb-6">
    Completed Modules
</h1>

@if($completedModules->isEmpty())
    <p class="text-gray-500">No completed modules.</p>
@else
    <div class="space-y-4">
        @foreach($completedModules as $module)
            <div class="rounded-xl bg-white shadow p-5 flex justify-between">
                <div>
                    <h2 class="font-semibold text-lg">
                        {{ $module->name }}
                    </h2>

                    <p class="text-sm text-gray-500">
                        Enrolled: {{ \Carbon\Carbon::parse($module->pivot->enrolled_at)->format('d M Y') }}
                    </p>

                    <p class="text-sm text-gray-500">
                        Completed: {{ \Carbon\Carbon::parse($module->pivot->completed_at)->format('d M Y') }}
                    </p>
                </div>

                <span class="px-4 py-1 rounded-full text-sm font-semibold
                    {{ $module->pivot->status === 'PASS'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-red-100 text-red-700' }}">
                    {{ $module->pivot->status }}
                </span>
            </div>
        @endforeach
    </div>
@endif

@endsection
