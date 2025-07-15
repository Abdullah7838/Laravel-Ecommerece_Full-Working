<div class="coupon-form mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Apply Coupon</h4>
        <a href="{{ route('coupon-help') }}" class="text-sm text-blue-600 hover:underline" target="_blank">
            <i class="bi bi-question-circle"></i> Help
        </a>
    </div>
    
    @if($appliedCoupon)
        <div class="applied-coupon p-3 mb-3 bg-light rounded">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-success me-2">{{ $appliedCoupon['code'] }}</span>
                    <span>
                        {{ $appliedCoupon['type'] === 'percentage' ? $appliedCoupon['value'] . '%' : 'PKR ' . number_format($appliedCoupon['value'], 2) }} off
                    </span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeCoupon">
                    <i class="bi bi-x-lg"></i> Remove
                </button>
            </div>
            <div class="mt-2">
                <strong>Discount:</strong> PKR {{ number_format($discountAmount, 2) }}
            </div>
        </div>
    @else
        <div class="input-group">
            <input type="text" class="form-control @error('couponCode') is-invalid @enderror" 
                   placeholder="Enter coupon code" 
                   wire:model="couponCode"
                   wire:keydown.enter="applyCoupon">
            <button class="btn btn-primary" type="button" wire:click="applyCoupon" wire:loading.attr="disabled">
                <span wire:loading wire:target="applyCoupon" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Apply
            </button>
        </div>
        @error('couponCode')
            <div class="invalid-feedback d-block">{{ $message }}</div>
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