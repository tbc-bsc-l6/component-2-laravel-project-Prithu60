<?php

namespace App\Http\Livewire\Pages\Auth;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle registration form submission.
     */
    public function register(): void
    {
        // Validate input
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Assign default "student" role
        $studentRole = UserRole::where('name', 'student')->first();
        if (!$studentRole) {
            abort(500, 'Student role not found. Please create it first.');
        }
        $validated['user_role_id'] = $studentRole->id;

        // Create user and fire registered event
        $user = User::create($validated);
        event(new Registered($user));

        // Login the new user
        Auth::login($user);

        // Redirect to dashboard
        $this->redirect(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.pages.auth.register');
    }
}
