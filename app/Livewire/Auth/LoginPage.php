<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Rule;

class LoginPage extends Component
{
    #[Rule('required|email')]
    public $email = '';
    
    #[Rule('required|min:8')]
    public $password = '';
    
    public function login()
    {
        $this->validate();
        
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->flash('success', 'You have been successfully logged in!');
            return $this->redirect('/', navigate: true);
        }
        
        session()->flash('error', 'Invalid credentials. Please try again.');
    }
    
    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
