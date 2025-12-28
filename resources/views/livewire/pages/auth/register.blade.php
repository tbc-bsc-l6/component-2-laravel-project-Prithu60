<?php

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // ✅ FIXED: use correct column name `role`
        $studentRole = UserRole::where('role', 'student')->firstOrFail();
        $validated['user_role_id'] = $studentRole->id;

        // Create user
        $user = User::create($validated);

        event(new Registered($user));

        // Ensure correct session
        Auth::logout();
        Auth::login($user);

        // Redirect to role-based dashboard
        $this->redirect('/dashboard');
    }
};
?>

<div>
    <form wire:submit.prevent="register" class="space-y-4">

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Name" />
            <x-text-input
                wire:model="name"
                id="name"
                type="text"
                class="block mt-1 w-full"
                required
                autofocus
            />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input
                wire:model="email"
                id="email"
                type="email"
                class="block mt-1 w-full"
                required
            />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Password" />
            <x-text-input
                wire:model="password"
                id="password"
                type="password"
                class="block mt-1 w-full"
                required
            />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Confirm Password" />
            <x-text-input
                wire:model="password_confirmation"
                id="password_confirmation"
                type="password"
                class="block mt-1 w-full"
                required
            />
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end">
            <a href="{{ route('login') }}"
               class="text-sm underline text-gray-600 hover:text-gray-900">
                Already registered?
            </a>

            <x-primary-button class="ms-4">
                Register
            </x-primary-button>
        </div>

    </form>
</div>
