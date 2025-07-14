<?php

namespace App\Livewire;

use Livewire\Component;

class SuccessPage extends Component
{
    public $orderItems = [];
    public $orderId;
    public $orderName;
    public $orderAddress;
    public $orderCity;
    public $orderState;
    public $orderZip;
    public $orderPhone;
    public $orderSubtotal;
    public $orderTax;
    public $orderShipping;
    public $orderTotal;
    public $paymentMethod;
    
    public function mount()
    {
        // Check if we have order information in the session
        if (!session()->has('order_id')) {
            return redirect()->route('index');
        }
        
        // Get order information from session
        $this->orderId = session('order_id');
        $this->orderName = session('order_name');
        $this->orderAddress = session('order_address');
        $this->orderCity = session('order_city');
        $this->orderState = session('order_state');
        $this->orderZip = session('order_zip');
        $this->orderPhone = session('order_phone');
        $this->orderSubtotal = session('order_subtotal');
        $this->orderTax = session('order_tax');
        $this->orderShipping = session('order_shipping');
        $this->orderTotal = session('order_total');
        $this->paymentMethod = session('payment_method');
        $this->orderItems = session('order_items', []);
    }
    
    public function render()
    {
        return view('livewire.success-page');
    }
}
