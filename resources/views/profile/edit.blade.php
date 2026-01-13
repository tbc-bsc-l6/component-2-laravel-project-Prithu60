@extends('layouts.admin')

@section('content')

<!-- ================= COMPACT GREEN GRADIENT HEADER ================= -->
<div class="relative mb-6 overflow-hidden rounded-2xl shadow">
    <div class="relative h-24 md:h-28">

        <!-- Gradient -->
        <div class="absolute inset-0 bg-[linear-gradient(110deg,#00b84a_0%,#0b7a46_40%,#064a33_65%,#0a0a0a_100%)]"></div>

        <!-- Soft glow -->
        <div class="absolute -left-20 -bottom-20 h-[240px] w-[240px]
                    bg-[radial-gradient(circle_at_center,rgba(0,255,140,0.35)_0%,rgba(0,255,140,0)_60%)]
                    blur-3xl"></div>

        <!-- Content -->
        <div class="relative z-10 flex h-full items-center justify-between px-6">
            <div>
                <h1 class="text-lg md:text-xl font-bold text-white">
                    Edit Module
                </h1>
                <p class="mt-1 text-xs md:text-sm text-white/85">
                    Update module details
                </p>
            </div>

            <div class="hidden md:flex items-center justify-center
                        rounded-full bg-white/15 px-3 py-1 text-xs text-white font-semibold">
                Module
            </div>
        </div>
    </div>
</div>

{{-- ================= FORM CONTAINER ================= --}}
<div class="max-w-3xl rounded-3xl bg-emerald-50/70 border border-emerald-100 p-6 shadow-sm">

    <div class="rounded-2xl bg-white border border-gray-100 shadow-sm p-6">

        <form method="POST"
              action="{{ route('admin.modules.update', $module) }}"
              class="space-y-4">
            @csrf
            @method('PUT')

            {{-- MODULE NAME --}}
            <div>
                <label class="block text-sm font-semibold text-gray-800">
                    Module Name
                </label>
                <input
                    name="name"
                    value="{{ old('name', $module->name) }}"
                    class="mt-1 w-full rounded-xl border-gray-300 px-4 py-2.5 text-sm
                           focus:border-emerald-500 focus:ring-emerald-500"
                >
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- DESCRIPTION --}}
            <div>
                <label class="block text-sm font-semibold text-gray-800">
                    Description
                </label>
                <textarea
                    name="description"
                    rows="3"
                    class="mt-1 w-full rounded-xl border-gray-300 px-4 py-2.5 text-sm
                           focus:border-emerald-500 focus:ring-emerald-500"
                >{{ old('description', $module->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- ACTIONS --}}
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.modules.index') }}"
                   class="rounded-xl border border-gray-300 px-4 py-2 text-sm
                          hover:bg-gray-50 transition">
                    Cancel
                </a>

                <button
                    class="rounded-xl bg-emerald-700 px-5 py-2 text-sm font-semibold text-white
                           hover:bg-emerald-800 transition">
                    Save Changes
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
