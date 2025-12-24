<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Module History
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded p-6">
                <h3 class="text-lg font-bold mb-4">
                    Completed Modules (PASS / FAIL)
                </h3>

                @if($completedModules->count() === 0)
                    <p class="text-gray-600">
                        You have not completed any modules yet.
                    </p>
                @else
                    <ul class="space-y-3">
                        @foreach($completedModules as $module)
                            <li class="flex items-center justify-between border rounded px-4 py-3">
                                <div>
                                    <p class="font-semibold">{{ $module->name }}</p>
                                    <p class="text-sm text-gray-600">
                                        Completed:
                                        {{ optional($module->pivot->completed_at)->format('Y-m-d') }}
                                    </p>
                                </div>

                                @if($module->pivot->status === 'PASS')
                                    <span class="px-3 py-1 rounded bg-green-100 text-green-800">
                                        PASS
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded bg-red-100 text-red-800">
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
