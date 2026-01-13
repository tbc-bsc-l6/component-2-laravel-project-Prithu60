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
                      d="M12 11c0 1.657-1.343 3-3 3s-3-1.343-3-3
                         1.343-3 3-3 3 1.343 3 3z
                         M19.4 15a7.97 7.97 0 01-6.4 3
                         7.97 7.97 0 01-6.4-3" />
            </svg>
        </div>
    </div>

    <form wire:submit.prevent="submit" class="mt-8 space-y-5">

        <div>
            <x-input-label value="Email" class="text-white font-semibold" />
            <x-text-input
                wire:model.defer="email"
                type="email"
                class="mt-2 w-full rounded-xl bg-white/90"
                placeholder="Enter email"
            />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label value="Password" class="text-white font-semibold" />
            <x-text-input
                wire:model.defer="password"
                type="password"
                class="mt-2 w-full rounded-xl bg-white/90"
                placeholder="Enter password"
            />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="flex items-center justify-between">
            <label class="inline-flex items-center gap-2 text-white/90">
                <input type="checkbox"
                       wire:model="remember"
                       class="rounded border-white/30 bg-white/20 text-indigo-500">
                <span class="text-sm">Remember me</span>
            </label>

            <a href="{{ route('password.request') }}"
               class="text-sm text-indigo-200 underline">
                Forgot password?
            </a>
        </div>

        <button type="submit"
                class="w-full bg-slate-900 text-white py-3 rounded-xl font-bold">
            LOG IN
        </button>

        <p class="text-center text-white text-sm">
            Don’t have an account?
            <a href="{{ route('register') }}" class="underline font-semibold">
                Register
            </a>
        </p>

    </form>
</div>
