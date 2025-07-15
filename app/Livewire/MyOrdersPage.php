<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyOrdersPage extends Component
{
    public $orders = [];
    
    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $this->loadOrders();
    }
    
    public function loadOrders()
    {
        $this->orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    public function render()
    {
        return view('livewire.my-orders-page');
    }
}
