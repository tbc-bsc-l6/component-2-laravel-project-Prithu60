@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Manage Teachers</h1>
    <p class="text-gray-500">Create teachers and assign modules</p>
</div>

@if(session('success'))
    <div class="mb-4 rounded bg-green-100 px-4 py-3 text-green-800">
        {{ session('success') }}
    </div>
@endif

<!-- CREATE TEACHER -->
<div class="bg-white rounded-xl shadow p-6 mb-8">
    <h2 class="text-lg font-semibold mb-4">Add New Teacher</h2>

    <form method="POST" action="{{ route('admin.teachers.store') }}" class="grid grid-cols-2 gap-4">
        @csrf

        <input name="first_name" placeholder="First Name"
               class="rounded border-gray-300 col-span-1">

        <input name="last_name" placeholder="Last Name"
               class="rounded border-gray-300 col-span-1">

        <input name="email" placeholder="Email"
               class="rounded border-gray-300 col-span-2">

        <input name="password" type="password" placeholder="Password"
               class="rounded border-gray-300 col-span-2">

        <div class="col-span-2">
            <label class="block font-medium text-gray-700 mb-2">
                Assign Modules (optional)
            </label>

            <div class="grid grid-cols-2 gap-2">
                @foreach($modules as $module)
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="modules[]"
                               value="{{ $module->id }}">
                        {{ $module->name }}
                    </label>
                @endforeach
            </div>
        </div>

        <button class="col-span-2 mt-4 bg-black text-white px-4 py-2 rounded">
            Create Teacher
        </button>
    </form>
</div>

<!-- TEACHER LIST -->
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-lg font-semibold mb-4">Teacher List</h2>

    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">Name</th>
                <th class="p-3 text-left">Email</th>
                <th class="p-3 text-left">Modules</th>
                <th class="p-3 text-left">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $teacher)
                <tr class="border-t">
                    <td class="p-3">{{ $teacher->name }}</td>
                    <td class="p-3">{{ $teacher->email }}</td>
                    <td class="p-3">
                        {{ $teacher->teachingModules->pluck('name')->join(', ') ?: '—' }}
                    </td>
                    <td class="p-3">
                        <form method="POST"
                              action="{{ route('admin.teachers.destroy', $teacher) }}">
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
@endsection
