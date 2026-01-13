<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class ConfirmPassword extends Component
{
    public string $password = '';

    public function submit(): void
    {
        if (! Hash::check($this->password, auth()->user()->password)) {
            $this->addError('password', 'Password does not match.');
            return;
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.pages.auth.confirm-password');
    }
}
