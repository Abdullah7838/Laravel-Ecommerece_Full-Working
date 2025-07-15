<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CouponUsageStats extends Component
{
    public $totalSavings = 0;
    public $ordersWithCoupons = 0;
    public $totalOrders = 0;
    public $mostUsedCoupon = null;
    
    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $this->loadStats();
    }
    
    public function loadStats()
    {
        $userId = Auth::id();
        
        // Get total savings from coupons
        $this->totalSavings = Order::where('user_id', $userId)
            ->where('discount_amount', '>', 0)
            ->sum('discount_amount');
        
        // Count orders with coupons
        $this->ordersWithCoupons = Order::where('user_id', $userId)
            ->whereNotNull('coupon_code')
            ->count();
        
        // Count total orders
        $this->totalOrders = Order::where('user_id', $userId)->count();
        
        // Get most used coupon
        $this->mostUsedCoupon = DB::table('orders')
            ->select('coupon_code', DB::raw('count(*) as total_uses'), DB::raw('sum(discount_amount) as total_savings'))
            ->where('user_id', $userId)
            ->whereNotNull('coupon_code')
            ->groupBy('coupon_code')
            ->orderBy('total_uses', 'desc')
            ->first();
    }
    
    public function render()
    {
        return view('livewire.coupon-usage-stats');
    }
}