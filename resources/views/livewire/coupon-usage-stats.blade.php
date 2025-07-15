<div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900 mt-4">
    <h2 class="text-xl font-bold text-gray-700 dark:text-white mb-4">Your Coupon Savings</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Total Savings Card -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-75">Total Savings</p>
                    <p class="text-2xl font-bold">PKR {{ number_format($totalSavings, 2) }}</p>
                </div>
                <div class="bg-white bg-opacity-30 rounded-full p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Orders with Coupons Card -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-75">Orders with Coupons</p>
                    <p class="text-2xl font-bold">{{ $ordersWithCoupons }} / {{ $totalOrders }}</p>
                </div>
                <div class="bg-white bg-opacity-30 rounded-full p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div class="mt-2">
                <div class="w-full bg-white bg-opacity-30 rounded-full h-2.5">
                    <div class="bg-white h-2.5 rounded-full" style="width: {{ $totalOrders > 0 ? ($ordersWithCoupons / $totalOrders) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        
        <!-- Most Used Coupon Card -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-75">Most Used Coupon</p>
                    @if($mostUsedCoupon)
                        <p class="text-2xl font-bold">{{ $mostUsedCoupon->coupon_code }}</p>
                        <p class="text-sm">Used {{ $mostUsedCoupon->total_uses }} times</p>
                        <p class="text-sm">Saved PKR {{ number_format($mostUsedCoupon->total_savings, 2) }}</p>
                    @else
                        <p class="text-lg font-medium">No coupons used yet</p>
                    @endif
                </div>
                <div class="bg-white bg-opacity-30 rounded-full p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4 text-center text-sm text-gray-500">
        <p>Use coupons during checkout to save on your orders!</p>
    </div>
</div>