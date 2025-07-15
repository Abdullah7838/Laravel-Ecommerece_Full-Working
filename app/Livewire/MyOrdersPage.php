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
        // Redirect to login if not authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Load orders for the authenticated user
        $this->orders = \App\Models\Order::where('user_id', auth()->id())
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        \Illuminate\Support\Facades\Log::info('Loaded ' . count($this->orders) . ' orders for user ID: ' . auth()->id());
    }
    
    // No longer needed as we load orders in mount method
    
    public function render()
    {
        return view('livewire.my-orders-page');
    }
}
