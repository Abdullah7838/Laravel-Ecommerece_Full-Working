<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyOrderDetailPage extends Component
{
    public $order;
    public $orderItems = [];
    public $address;
    
    public function mount($order)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $this->order = Order::with(['items.product', 'address', 'user'])
            ->where('id', $order)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $this->orderItems = $this->order->items;
        $this->address = $this->order->address;
    }
    
    public function render()
    {
        return view('livewire.my-order-detail-page');
    }
}
