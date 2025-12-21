<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\LoginForm;
use Livewire\Component;

class Login extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        session()->regenerate();

        $role = auth()->user()?->role?->name;

        match ($role) {
            'admin' => $this->redirect(route('admin.dashboard'), navigate: true),
            'teacher' => $this->redirect(route('teacher.dashboard'), navigate: true),
            'student' => $this->redirect(route('student.dashboard'), navigate: true),
            'old_student' => $this->redirect(route('student.modules.history'), navigate: true),
            default => $this->redirect(route('dashboard'), navigate: true),
        };
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
