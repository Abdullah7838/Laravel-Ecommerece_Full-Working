<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AccountPage extends Component
{
    public function render()
    {
        return view('livewire.auth.account-page')
            ->title('My Account - Wibzr');
    }
}