<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;

class ForgotPassword extends Component
{
    public string $email = '';

    public function submit(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
        ]);

        Password::sendResetLink([
            'email' => $this->email,
        ]);

        session()->flash(
            'status',
            'Password reset link has been sent to your email.'
        );
    }

    public function render()
    {
        return view('livewire.pages.auth.forgot-password');
    }
}
