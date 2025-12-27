@extends('layouts.admin')

@section('title', 'Enroll Student')
@section('header', 'Enroll Student')

@section('content')

<div class="max-w-xl bg-white rounded-xl shadow p-6">

    <p class="text-sm text-gray-500 mb-6">
        Enroll a student into this module
    </p>

    <form method="POST" action="{{ route('admin.modules.enroll', $module) }}">
        @csrf

        {{-- Student Selection --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Select Student
            </label>

            <select
                name="student_id"
                class="w-full border-gray-300 rounded-lg shadow-sm
                       focus:border-indigo-500 focus:ring-indigo-500"
                required
            >
                <option value="">-- Choose Student --</option>

                @foreach($students as $student)
                    <option value="{{ $student->id }}">
                        {{ $student->name }} ({{ $student->email }})
                    </option>
                @endforeach
            </select>

            @error('student_id')
                <p class="text-sm text-red-600 mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Actions --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.modules.index') }}"
               class="px-4 py-2 rounded-lg border border-gray-300
                      text-gray-700 hover:bg-gray-100 transition">
                Cancel
            </a>

            <button
                type="submit"
                class="px-4 py-2 rounded-lg
                       bg-indigo-600 text-white
                       hover:bg-indigo-700 transition">
                Enroll Student
            </button>
        </div>

    </form>
</div>

@endsection
