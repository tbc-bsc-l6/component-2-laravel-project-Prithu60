<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Manage Teachers
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">

        {{-- Create Teacher --}}
        <div class="bg-white p-6 shadow rounded mb-6">
            <h3 class="text-lg font-medium mb-4">Add New Teacher</h3>

            <form method="POST" action="{{ route('admin.teachers.store') }}" class="space-y-4">
                @csrf

                <input name="name" placeholder="Name" class="w-full border rounded p-2" required>
                <input name="email" placeholder="Email" class="w-full border rounded p-2" required>
                <input name="password" type="password" placeholder="Password" class="w-full border rounded p-2" required>

                <button class="bg-black text-white px-4 py-2 rounded">
                    Create Teacher
                </button>
            </form>
        </div>

        {{-- Teacher List --}}
        <div class="bg-white p-6 shadow rounded">
            <h3 class="text-lg font-medium mb-4">Teacher List</h3>

            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 border">Name</th>
                        <th class="p-2 border">Email</th>
                        <th class="p-2 border">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($teachers as $teacher)
                        <tr>
                            <td class="p-2 border">{{ $teacher->name }}</td>
                            <td class="p-2 border">{{ $teacher->email }}</td>
                            <td class="p-2 border">
                                <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
