<x-teacher-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Modules
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($modules->isEmpty())
                <div class="bg-white p-6 rounded shadow text-gray-600">
                    You have not been assigned any modules yet.
                </div>
            @else
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left px-6 py-3">Module</th>
                                <th class="text-right px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($modules as $module)
                                <tr class="border-t">
                                    <td class="px-6 py-4">
                                        {{ $module->module }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a
                                            href="{{ route('teacher.modules.students', $module->id) }}"
                                            class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
                                        >
                                            View Students
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</x-teacher-layout>
