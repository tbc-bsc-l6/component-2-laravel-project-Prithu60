<div>
    <div class="rounded-3xl bg-white/15 backdrop-blur-xl shadow-2xl px-8 py-10">

        <h1 class="text-4xl font-extrabold text-white text-center tracking-wide">
            Edu World
        </h1>

        <div class="mt-5 flex justify-center">
            <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-9 w-9 text-indigo-200"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16 14a4 4 0 10-8 0m8 0v1a4 4 0 01-8 0v-1m8 0a6 6 0 01-8 0" />
                </svg>
            </div>
        </div>

        {{-- ✅ MUST MATCH submit() METHOD --}}
        <form wire:submit.prevent="submit" class="mt-8 space-y-5">

            <div>
                <x-input-label value="Name" class="text-white font-semibold" />
                <x-text-input
                    wire:model.defer="name"
                    class="mt-2 w-full rounded-xl bg-white/90"
                    type="text"
                    placeholder="Enter name" />
                <x-input-error :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label value="Email" class="text-white font-semibold" />
                <x-text-input
                    wire:model.defer="email"
                    class="mt-2 w-full rounded-xl bg-white/90"
                    type="email"
                    placeholder="Enter email" />
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div>
                <x-input-label value="Password" class="text-white font-semibold" />
                <x-text-input
                    wire:model.defer="password"
                    class="mt-2 w-full rounded-xl bg-white/90"
                    type="password"
                    placeholder="Enter password" />
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <div>
                <x-input-label value="Confirm Password" class="text-white font-semibold" />
                <x-text-input
                    wire:model.defer="password_confirmation"
                    class="mt-2 w-full rounded-xl bg-white/90"
                    type="password"
                    placeholder="Confirm password" />
            </div>

            <button type="submit"
                    class="w-full bg-slate-900 text-white py-3 rounded-xl font-bold">
                REGISTER
            </button>

            <p class="text-center text-white text-sm">
                Already registered?
                <a href="{{ route('login') }}" class="underline font-semibold">
                    Log in
                </a>
            </p>

        </form>
    </div>
</div>
