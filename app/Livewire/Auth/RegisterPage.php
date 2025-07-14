<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Rule;

class RegisterPage extends Component
{
    #[Rule('required|min:3')]
    public $name = '';
    
    #[Rule('required|email|unique:users,email')]
    public $email = '';
    
    #[Rule('required|min:8')]
    public $password = '';
    
    public function register()
    {
        $this->validate();
        
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
        
        Auth::login($user);
        
        session()->flash('success', 'Account created successfully!');
        return $this->redirect('/', navigate: true);
    }
    
    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
