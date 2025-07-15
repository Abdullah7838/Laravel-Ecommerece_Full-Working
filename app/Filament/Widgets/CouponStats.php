<?php

namespace App\Filament\Widgets;

use App\Models\Coupon;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class CouponStats extends BaseWidget
{
    protected static ?int $sort = 4;
    
    protected function getStats(): array
    {
        $activeCoupons = Coupon::where('is_active', true)->count();
        $expiredCoupons = Coupon::where('is_active', true)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->count();
            
        $totalDiscountAmount = Order::sum('discount_amount');
        
        $mostUsedCoupon = DB::table('orders')
            ->select('coupon_code', DB::raw('count(*) as total_uses'))
            ->whereNotNull('coupon_code')
            ->groupBy('coupon_code')
            ->orderBy('total_uses', 'desc')
            ->first();
            
        $mostUsedCouponText = $mostUsedCoupon 
            ? $mostUsedCoupon->coupon_code . ' (' . $mostUsedCoupon->total_uses . ' uses)'
            : 'No coupons used yet';
        
        return [
            Stat::make('Active Coupons', $activeCoupons)
                ->description('Currently active coupons')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('success'),
                
            Stat::make('Expired Coupons', $expiredCoupons)
                ->description('Coupons past expiration date')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),
                
            Stat::make('Total Discount Amount', 'PKR ' . number_format($totalDiscountAmount, 2))
                ->description('Total discount given to customers')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'),
                
            Stat::make('Most Used Coupon', $mostUsedCouponText)
                ->description('Coupon with highest usage')
                ->descriptionIcon('heroicon-m-star')
                ->color('primary'),
        ];
    }
}