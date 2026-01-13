<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class VerifyEmail extends Component
{
    public function resend(): void
    {
        Auth::user()->sendEmailVerificationNotification();

        session()->flash('status', 'Verification email sent.');
    }

    public function render()
    {
        return view('livewire.pages.auth.verify-email');
    }
}
