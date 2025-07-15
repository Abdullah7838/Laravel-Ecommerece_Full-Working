<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Models\Coupon;
use Livewire\Component;
use Livewire\Attributes\Rule;

class ApplyCoupon extends Component
{
    #[Rule('nullable|min:3')]
    public $couponCode = '';
    
    public $discount = 0;
    public $discountType = '';
    public $discountAmount = 0;
    public $couponError = '';
    public $couponSuccess = '';
    public $appliedCoupon = null;
    
    public function mount()
    {
        // Check if there's a coupon in the session
        if (session()->has('coupon')) {
            $couponData = session('coupon');
            $this->appliedCoupon = $couponData;
            $this->couponCode = $couponData['code'];
            $this->discountType = $couponData['type'];
            $this->discount = $couponData['value'];
            $this->discountAmount = $couponData['discount_amount'];
            $this->couponSuccess = 'Coupon applied: ' . $couponData['code'];
        }
    }
    
    public function applyCoupon()
    {
        $this->resetValidation();
        $this->couponError = '';
        $this->couponSuccess = '';
        
        $this->validate();
        
        // Find the coupon
        $coupon = Coupon::where('code', $this->couponCode)->first();
        
        if (!$coupon) {
            $this->couponError = 'Invalid coupon code';
            return;
        }
        
        // Get cart total
        $cartItems = CartManagement::getCartItemsFromCookie();
        $cartTotal = CartManagement::calculateGrandTotal($cartItems);
        
        // Check if coupon is valid
        if (!$coupon->isValid($cartTotal)) {
            if ($coupon->min_order_amount !== null && $cartTotal < $coupon->min_order_amount) {
                $this->couponError = 'Minimum order amount for this coupon is PKR ' . number_format($coupon->min_order_amount, 2);
            } elseif (!$coupon->is_active) {
                $this->couponError = 'This coupon is not active';
            } elseif ($coupon->max_uses !== null && $coupon->used_count >= $coupon->max_uses) {
                $this->couponError = 'This coupon has reached its maximum usage limit';
            } elseif ($coupon->starts_at !== null && now() < $coupon->starts_at) {
                $this->couponError = 'This coupon is not valid yet';
            } elseif ($coupon->expires_at !== null && now() > $coupon->expires_at) {
                $this->couponError = 'This coupon has expired';
            } else {
                $this->couponError = 'Invalid coupon';
            }
            return;
        }
        
        // Calculate discount
        $discountAmount = $coupon->calculateDiscount($cartTotal);
        
        // Store coupon in session
        session()->put('coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'discount_amount' => $discountAmount
        ]);
        
        $this->appliedCoupon = session('coupon');
        $this->discountType = $coupon->type;
        $this->discount = $coupon->value;
        $this->discountAmount = $discountAmount;
        $this->couponSuccess = 'Coupon applied: ' . $coupon->code;
        
        // Emit event to update checkout totals
        $this->dispatch('coupon-applied', [
            'discount_amount' => $discountAmount
        ]);
    }
    
    public function removeCoupon()
    {
        // Remove coupon from session
        session()->forget('coupon');
        
        // Reset component properties
        $this->appliedCoupon = null;
        $this->couponCode = '';
        $this->discountType = '';
        $this->discount = 0;
        $this->discountAmount = 0;
        $this->couponSuccess = '';
        $this->couponError = '';
        
        // Emit event to update checkout totals
        $this->dispatch('coupon-removed');
    }
    
    public function render()
    {
        return view('livewire.apply-coupon');
    }
}