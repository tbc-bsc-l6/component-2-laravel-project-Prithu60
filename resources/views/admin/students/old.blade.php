<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Old Students
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded p-6">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-100">
                            <th class="text-left py-2 px-2">Name</th>
                            <th class="text-left py-2 px-2">Email</th>
                            <th class="text-left py-2 px-2">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($students as $student)
                            <tr class="border-b">
                                <td class="py-2 px-2">{{ $student->name }}</td>
                                <td class="py-2 px-2">{{ $student->email }}</td>
                                <td class="py-2 px-2 font-medium text-gray-700">
                                    Old Student
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-gray-500 py-4">
                                    No old students found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
