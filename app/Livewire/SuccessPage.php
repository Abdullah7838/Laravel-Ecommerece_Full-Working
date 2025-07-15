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
            session()->flash('error', 'No order information found');
            return redirect()->route('index');
        }
        
        // Get order from database
        $orderId = session('order_id');
        $order = \App\Models\Order::with(['items.product', 'address'])->find($orderId);
        
        if (!$order) {
            \Illuminate\Support\Facades\Log::error('Order not found in database: ' . $orderId);
            session()->flash('error', 'Order not found in database');
            return redirect()->route('index');
        }
        
        // Log successful order retrieval
        \Illuminate\Support\Facades\Log::info('Order retrieved successfully: ' . $orderId);
        
        // Set order information from database
        $this->orderId = $order->id;
        $this->orderName = $order->address->first_name . ' ' . $order->address->last_name;
        $this->orderAddress = $order->address->street_address;
        $this->orderCity = $order->address->city;
        $this->orderState = $order->address->state;
        $this->orderZip = $order->address->zip_code;
        $this->orderPhone = $order->address->phone;
        $this->orderSubtotal = number_format($order->subtotal, 2);
        $this->orderTax = number_format(0, 2); // No tax
        $this->orderShipping = number_format($order->shipping_amount, 2);
        $this->orderTotal = number_format($order->grand_total, 2);
        $this->paymentMethod = $order->payment_method;
        
        // Get order items from database
        $this->orderItems = $order->items->map(function($item) {
            return [
                'id' => $item->product_id,
                'name' => $item->product->name,
                'price' => $item->unit_amount,
                'quantity' => $item->quantity,
                'image' => $item->product->image,
                'total' => $item->total_amount
            ];
        })->toArray();
    }
    
    public function render()
    {
        return view('livewire.success-page');
    }
}
