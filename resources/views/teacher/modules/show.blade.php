<x-teacher-layout>
    <x-slot name="header">
        {{ $module->name }} — Enrolled Students
    </x-slot>

    <div class="mb-4">
        <a href="{{ route('teacher.modules.index') }}"
           class="text-sm text-indigo-600 underline">
            ← Back to Modules
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 text-left">Enrolled At</th>
                    <th class="p-3 text-left">Result</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr class="border-t">
                        <td class="p-3">{{ $student->name }}</td>
                        <td class="p-3">{{ $student->email }}</td>
                        <td class="p-3">
                            {{ optional($student->pivot->enrolled_at)->format('d M Y') }}
                        </td>
                        <td class="p-3 flex gap-2">
                            <form method="POST"
                                  action="{{ route('teacher.modules.students.pass', [$module, $student]) }}">
                                @csrf
                                <button class="px-3 py-1 bg-green-600 text-white rounded">
                                    PASS
                                </button>
                            </form>

                            <form method="POST"
                                  action="{{ route('teacher.modules.students.fail', [$module, $student]) }}">
                                @csrf
                                <button class="px-3 py-1 bg-red-600 text-white rounded">
                                    FAIL
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500">
                            No students enrolled.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-teacher-layout>
