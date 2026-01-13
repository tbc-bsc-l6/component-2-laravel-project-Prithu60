<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;

class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function submit(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $role = UserRole::where('role', 'student')->firstOrFail();
        $validated['user_role_id'] = $role->id;

        $user = User::create($validated);

        event(new Registered($user));
        Auth::login($user);

        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.pages.auth.register');
    }
}
