<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class CartPage extends Component
{
    public $cartItems = [];
    public $grandTotal = 0;
    
    public function mount()
    {
        $this->loadCartItems();
    }
    
    public function loadCartItems()
    {
        $this->cartItems = CartManagement::getCartItemsFromCookie();
        $this->grandTotal = CartManagement::calculateGrandTotal($this->cartItems);
    }
    
    #[Livewire\Attributes\Renderless]
    public function incrementQuantity($productId)
    {
        CartManagement::incrementQuantityToCartItem($productId);
        $this->loadCartItems();
        $this->dispatch('cart-updated');
    }
    
    #[Livewire\Attributes\Renderless]
    public function decrementQuantity($productId)
    {
        CartManagement::decrementQuantityToCartItem($productId);
        $this->loadCartItems();
        $this->dispatch('cart-updated');
    }
    
    #[Livewire\Attributes\Renderless]
    public function removeItem($productId)
    {
        CartManagement::removeCartItems($productId);
        $this->loadCartItems();
        $this->dispatch('cart-updated');
    }
    
    public function proceedToCheckout()
    {
        if (!Auth::check()) {
            // Store intended URL in session
            session()->put('intended_url', '/checkout');
            return redirect()->route('login');
        }
        
        return redirect()->to('/checkout');
    }
    
    public function render()
    {
        return view('livewire.cart-page');
    }
}
