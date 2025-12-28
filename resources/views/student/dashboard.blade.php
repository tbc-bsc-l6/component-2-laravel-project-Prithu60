<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Student Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- FLASH MESSAGES --}}
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

            {{-- ================= ENROLLED MODULES ================= --}}
            <div class="bg-white shadow rounded p-6">
                <h3 class="text-lg font-bold mb-4">
                    Currently Enrolled Modules
                </h3>

                @forelse($enrolledModules as $module)
                    <div class="border rounded px-4 py-3 mb-3 flex justify-between items-center">
                        <div>
                            <p class="font-semibold">{{ $module->name }}</p>

                            <p class="text-sm text-gray-600">
                                Enrolled on:
                                {{ optional($module->pivot->enrolled_at)->format('d M Y') }}
                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                Students enrolled:
                                {{ $module->enrolled_students_count }} / 10
                            </p>
                        </div>

                        <span class="px-3 py-1 rounded text-sm bg-blue-100 text-blue-800">
                            ENROLLED
                        </span>
                    </div>
                @empty
                    <p class="text-gray-600">
                        You are not enrolled in any modules.
                    </p>
                @endforelse
            </div>

            {{-- ================= AVAILABLE MODULES ================= --}}
            <div class="bg-white shadow rounded p-6">
                <h3 class="text-lg font-bold mb-4">
                    Available Modules
                </h3>

                @forelse($availableModules as $module)
                    <div class="border rounded px-4 py-3 mb-3 flex justify-between items-center">
                        <div>
                            <p class="font-semibold">{{ $module->name }}</p>

                            <p class="text-xs text-gray-500 mt-1">
                                Students enrolled:
                                {{ $module->enrolled_students_count }} / 10
                            </p>
                        </div>

                        {{-- BUTTON LOGIC --}}
                        @if(auth()->user()->activeModules()->count() >= 4)
                            <button disabled
                                class="px-4 py-2 rounded bg-gray-200 text-gray-600 cursor-not-allowed">
                                Max 4 reached
                            </button>

                        @elseif($module->enrolled_students_count >= 10)
                            <button disabled
                                class="px-4 py-2 rounded bg-gray-200 text-gray-600 cursor-not-allowed">
                                Module full
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
                    </div>
                @empty
                    <p class="text-gray-600">
                        No modules available.
                    </p>
                @endforelse
            </div>

            {{-- ================= COMPLETED MODULES ================= --}}
            <div class="bg-white shadow rounded p-6">
                <h3 class="text-lg font-bold mb-4">
                    Completed Modules (PASS / FAIL)
                </h3>

                @forelse($completedModules as $module)
                    <div class="border rounded px-4 py-3 mb-3 flex justify-between items-center">
                        <div>
                            <p class="font-semibold">{{ $module->name }}</p>

                            <p class="text-sm text-gray-600">
                                Completed on:
                                {{ optional($module->pivot->completed_at)->format('d M Y') }}
                            </p>
                        </div>

                        <span class="px-3 py-1 rounded text-sm
                            {{ $module->pivot->status === 'PASS'
                                ? 'bg-green-100 text-green-800'
                                : 'bg-red-100 text-red-800' }}">
                            {{ $module->pivot->status }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-600">
                        No completed modules yet.
                    </p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
