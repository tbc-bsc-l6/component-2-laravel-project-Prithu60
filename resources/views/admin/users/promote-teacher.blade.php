@extends('layouts.admin')

@section('content')

<div class="relative mb-6 overflow-hidden rounded-2xl shadow">
    <div class="relative h-28 md:h-32">
        <div class="absolute inset-0 bg-[linear-gradient(110deg,#00b84a_0%,#0b7a46_35%,#064a33_60%,#0a0a0a_100%)]"></div>

        <div class="relative z-10 flex h-full items-center justify-between px-6 md:px-8">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-white leading-tight">
                    Assign Teacher Modules
                </h1>
                <p class="mt-1 text-xs md:text-sm text-white/85">
                    Promote <span class="font-semibold">{{ $user->name }}</span> to Teacher and assign module(s)
                </p>
            </div>
        </div>
    </div>
</div>

@if ($errors->any())
    <div class="mb-6 rounded-xl bg-red-100 px-6 py-4 text-red-800 border border-red-200 text-sm">
        Please select at least one module.
    </div>
@endif

<div class="rounded-3xl bg-emerald-50/70 border border-emerald-100 p-6 shadow-sm">
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-gray-100 p-6 max-w-xl">

        <form method="POST" action="{{ route('admin.users.promote-teacher.store', $user) }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-800 mb-2">
                    Select module(s) to assign
                </label>

                <div class="space-y-2">
                    @foreach($modules as $module)
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="modules[]" value="{{ $module->id }}"
                                   class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            <span>{{ $module->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="rounded-lg bg-gray-900 px-4 py-2 text-sm text-white hover:bg-black transition">
                    Promote & Assign
                </button>

                <a href="{{ route('admin.students.index') }}"
                   class="rounded-lg bg-gray-200 px-4 py-2 text-sm text-gray-800 hover:bg-gray-300 transition">
                    Cancel
                </a>
            </div>
        </form>

    </div>
</div>

@endsection
