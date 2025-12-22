<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Manage Modules
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto space-y-6">

        <!-- Create Module -->
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-semibold mb-4">Add New Module</h3>

            <form method="POST" action="{{ route('admin.modules.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium">Module Name</label>
                    <input type="text" name="name"
                           class="w-full border rounded px-3 py-2"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Description</label>
                    <textarea name="description"
                              class="w-full border rounded px-3 py-2"></textarea>
                </div>

                <button class="bg-black text-white px-4 py-2 rounded">
                    Create Module
                </button>
            </form>
        </div>

        <!-- Module List -->
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-semibold mb-4">All Modules</h3>

            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">Name</th>
                        <th class="border p-2">Teachers</th>
                        <th class="border p-2">Status</th>
                        <th class="border p-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($modules as $module)
                    <tr>
                        <td class="border p-2">{{ $module->name }}</td>

                        <td class="border p-2">
                            @forelse($module->teachers as $teacher)
                                <span class="block">{{ $teacher->name }}</span>
                            @empty
                                <span class="text-gray-500">No teacher</span>
                            @endforelse
                        </td>

                        <td class="border p-2">
                            {{ $module->available ? 'Available' : 'Archived' }}
                        </td>

                        <td class="border p-2">
                            <form method="POST"
                                  action="{{ route('admin.modules.toggleAvailability', $module) }}">
                                @csrf
                                <button class="text-blue-600">
                                    Toggle
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center p-4">
                            No modules found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
