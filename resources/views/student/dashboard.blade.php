<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Student Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 text-red-800 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- 1) Enrolled Modules --}}
            <div class="bg-white shadow rounded p-6">
                <h3 class="text-lg font-bold mb-4">Currently Enrolled Modules</h3>

                @if($enrolledModules->count() === 0)
                    <p class="text-gray-600">
                        You are not enrolled in any module right now.
                    </p>
                @else
                    <ul class="space-y-3">
                        @foreach($enrolledModules as $module)
                            <li class="flex items-center justify-between border rounded px-4 py-3">
                                <div>
                                    <p class="font-semibold">{{ $module->name }}</p>
                                    <p class="text-sm text-gray-600">
                                        Enrolled:
                                        {{ optional($module->pivot->enrolled_at)->format('Y-m-d') }}
                                    </p>
                                </div>

                                <span class="px-3 py-1 rounded text-sm bg-blue-100 text-blue-800">
                                    ENROLLED
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- 2) Available Modules --}}
            <div class="bg-white shadow rounded p-6">
                <h3 class="text-lg font-bold mb-4">Available Modules</h3>

                @if($availableModules->count() === 0)
                    <p class="text-gray-600">
                        No modules are currently available.
                    </p>
                @else
                    <ul class="space-y-3">
                        @foreach($availableModules as $module)
                            <li class="flex items-center justify-between border rounded px-4 py-3">
                                <div class="pr-4">
                                    <p class="font-semibold">{{ $module->name }}</p>

                                    @if($module->description)
                                        <p class="text-sm text-gray-600">
                                            {{ $module->description }}
                                        </p>
                                    @endif

                                    <p class="text-xs text-gray-500 mt-1">
                                        Capacity:
                                        {{ $module->enrolledStudentsCount() }} / 10
                                    </p>
                                </div>

                                @php
                                    $isFull  = $module->isFull();
                                    $atLimit = $enrolledModules->count() >= 4;
                                @endphp

                                @if($atLimit)
                                    <button disabled
                                        class="px-4 py-2 rounded bg-gray-200 text-gray-600 cursor-not-allowed">
                                        Max 4 reached
                                    </button>
                                @elseif($isFull)
                                    <button disabled
                                        class="px-4 py-2 rounded bg-gray-200 text-gray-600 cursor-not-allowed">
                                        Module Full
                                    </button>
                                @else
                                    <form method="POST"
                                          action="{{ route('student.modules.enroll', $module) }}">
                                        @csrf
                                        <button
                                            class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                            Enroll
                                        </button>
                                    </form>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- 3) Completed Modules --}}
            <div class="bg-white shadow rounded p-6">
                <h3 class="text-lg font-bold mb-4">
                    Completed Modules (PASS / FAIL)
                </h3>

                @if($completedModules->count() === 0)
                    <p class="text-gray-600">
                        No completed modules yet.
                    </p>
                @else
                    <ul class="space-y-3">
                        @foreach($completedModules as $module)
                            <li class="flex items-center justify-between border rounded px-4 py-3">
                                <div>
                                    <p class="font-semibold">{{ $module->name }}</p>
                                    <p class="text-sm text-gray-600">
                                        Completed:
                                        {{ optional($module->pivot->completed_at)->format('Y-m-d') ?? '—' }}
                                    </p>
                                </div>

                                @if($module->pivot->status === 'PASS')
                                    <span
                                        class="px-3 py-1 rounded text-sm bg-green-100 text-green-800">
                                        PASS
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1 rounded text-sm bg-red-100 text-red-800">
                                        FAIL
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
