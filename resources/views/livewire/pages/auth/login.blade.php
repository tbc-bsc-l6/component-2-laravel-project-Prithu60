<div class="min-h-screen flex items-center justify-center px-4
            bg-cover bg-center bg-no-repeat"
     style="background-image: url('{{ asset('images/login-bg.jpg') }}');">

    <!-- Dark overlay -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

    <!-- Glass card -->
    <div class="relative w-full max-w-md
                rounded-2xl
                bg-white/20 backdrop-blur-xl
                shadow-2xl
                border border-white/30
                p-8">

        <!-- Title -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-white">
                Edu World
            </h1>
            <p class="text-sm text-white/80 mt-1">
                Sign in to your account
            </p>
        </div>

        <!-- Form -->
        <form wire:submit.prevent="submit" class="space-y-5">

            <!-- Email -->
            <div>
                <label class="block text-sm font-semibold text-white mb-1">
                    Email
                </label>
                <input
                    type="email"
                    wire:model.defer="email"
                    placeholder="Enter email"
                    class="w-full rounded-lg
                           bg-white/80 text-slate-800
                           px-4 py-2.5
                           border border-white/40
                           focus:ring-2 focus:ring-emerald-400
                           outline-none"
                >
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-200"/>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-semibold text-white mb-1">
                    Password
                </label>
                <input
                    type="password"
                    wire:model.defer="password"
                    placeholder="Enter password"
                    class="w-full rounded-lg
                           bg-white/80 text-slate-800
                           px-4 py-2.5
                           border border-white/40
                           focus:ring-2 focus:ring-emerald-400
                           outline-none"
                >
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-200"/>
            </div>

            <!-- Remember + Forgot -->
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 text-white/90">
                    <input type="checkbox"
                           wire:model="remember"
                           class="rounded border-white/50 text-emerald-500">
                    Remember me
                </label>

                <a href="{{ route('password.request') }}"
                   class="text-emerald-300 hover:underline">
                    Forgot password?
                </a>
            </div>

            <!-- LOGIN BUTTON (VERY VISIBLE) -->
            <button
                type="submit"
                class="w-full mt-4
                       rounded-lg
                       bg-emerald-600 hover:bg-emerald-700
                       text-white py-3
                       font-bold tracking-wide
                       shadow-xl
                       transition">
                LOG IN
            </button>

        </form>

        <!-- Footer -->
        <p class="mt-6 text-center text-sm text-white/90">
            Don’t have an account?
            <a href="{{ route('register') }}"
               class="text-emerald-300 font-semibold hover:underline">
                Register
            </a>
        </p>

    </div>
</div>
