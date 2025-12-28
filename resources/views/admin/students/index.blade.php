<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Manage Students
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded p-6">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-100">
                            <th class="text-left py-2 px-2">Name</th>
                            <th class="text-left py-2 px-2">Email</th>
                            <th class="text-left py-2 px-2">Current Role</th>
                            <th class="text-left py-2 px-2">Change Role</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($students as $student)
                            <tr class="border-b">
                                <td class="py-2 px-2">{{ $student->name }}</td>
                                <td class="py-2 px-2">{{ $student->email }}</td>

                                <td class="py-2 px-2 font-medium">
                                    {{ ucfirst(str_replace('_', ' ', $student->role->role)) }}
                                </td>

                                <td class="py-2 px-2">
                                    <form method="POST"
                                          action="{{ route('admin.students.updateRole', $student) }}"
                                          class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')

                                        <select name="role_id"
                                                class="border rounded px-2 py-1">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}"
                                                    @selected($student->user_role_id === $role->id)>
                                                    {{ ucfirst(str_replace('_', ' ', $role->role)) }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button type="submit"
                                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded">
                                            Update
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-gray-500 py-4">
                                    No students found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
