<div class="coupon-form mb-4">
    <div class="flex justify-between items-center mb-3">
        <h4 class="text-lg font-semibold text-gray-700 dark:text-white">Apply Coupon (Optional)</h4>
        <a href="{{ route('coupon-help') }}" class="text-sm text-blue-600 hover:underline" target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Help
        </a>
    </div>
    
    @if($appliedCoupon)
        <div class="p-3 mb-3 bg-green-50 dark:bg-gray-700 rounded-lg border border-green-200 dark:border-gray-600">
            <div class="flex justify-between items-center">
                <div>
                    <span class="bg-green-500 text-white px-2 py-1 rounded-md text-sm mr-2">{{ $appliedCoupon['code'] }}</span>
                    <span class="text-gray-700 dark:text-gray-300">
                        {{ $appliedCoupon['type'] === 'percentage' ? $appliedCoupon['value'] . '%' : 'PKR ' . number_format($appliedCoupon['value'], 2) }} off
                    </span>
                </div>
                <button type="button" class="text-red-500 hover:text-red-700 px-2 py-1 rounded-md border border-red-300 hover:bg-red-50 dark:hover:bg-gray-600" wire:click="removeCoupon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Remove
                </button>
            </div>
            <div class="mt-2 text-gray-700 dark:text-gray-300">
                <strong>Discount:</strong> PKR {{ number_format($discountAmount, 2) }}
            </div>
        </div>
    @else
        <div class="flex">
            <input type="text" class="flex-grow rounded-l-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-gray-600 {{ $errors->has('couponCode') ? 'border-red-500' : 'border-gray-300' }}" 
                   placeholder="Enter coupon code (optional)" 
                   wire:model="couponCode"
                   wire:keydown.enter="applyCoupon">
            <button class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-r-lg" type="button" wire:click="applyCoupon" wire:loading.attr="disabled">
                <span wire:loading wire:target="applyCoupon" class="inline-block animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full mr-1"></span>
                Apply
            </button>
        </div>
        @error('couponCode')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
        @enderror
    @endif
    
    @if($couponError)
        <div class="alert alert-danger mt-2 py-2 small">
            {{ $couponError }}
        </div>
    @endif
    
    @if($couponSuccess && !$couponError)
        <div class="alert alert-success mt-2 py-2 small">
            {{ $couponSuccess }}
        </div>
    @endif
</div>