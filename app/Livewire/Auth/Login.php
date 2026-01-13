<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function submit(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt(
            ['email' => $this->email, 'password' => $this->password],
            $this->remember
        )) {
            $this->addError('email', 'Invalid credentials.');
            return;
        }

        request()->session()->regenerate();

        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.pages.auth.login');
    }
}
