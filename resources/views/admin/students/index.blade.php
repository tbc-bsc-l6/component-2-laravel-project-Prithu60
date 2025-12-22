<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Manage Students
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <div class="bg-white p-6 shadow rounded">
            <table class="w-full border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2">Name</th>
                        <th class="border p-2">Email</th>
                        <th class="border p-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td class="border p-2">{{ $student->name }}</td>
                            <td class="border p-2">{{ $student->email }}</td>
                            <td class="border p-2">
                                {{ ucfirst($student->role->name) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center p-4">
                                No students found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
