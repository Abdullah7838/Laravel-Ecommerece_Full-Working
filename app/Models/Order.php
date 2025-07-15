<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Specifying the database table associated with this model
    protected $table = "orders";

    protected $fillable = [
        'user_id',
        'coupon_id',
        'coupon_code',
        'discount_amount',
        'subtotal',
        'grand_total',
        'payment_method',
        'payment_status',
        'status',
        'currency',
        'shipping_amount',
        'shipping_method',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Calculate the order subtotal (sum of all items)
     */
    public function calculateSubtotal()
    {
        return $this->items->sum(function ($item) {
            return $item->unit_amount * $item->quantity;
        });
    }

    /**
     * Apply a coupon to the order
     */
    public function applyCoupon(Coupon $coupon)
    {
        if (!$coupon->isValid($this->calculateSubtotal())) {
            return false;
        }

        $this->coupon_id = $coupon->id;
        $this->coupon_code = $coupon->code;
        $this->discount_amount = $coupon->calculateDiscount($this->calculateSubtotal());
        $this->save();

        // Increment the coupon usage count
        $coupon->incrementUsedCount();

        return true;
    }

    /**
     * Remove a coupon from the order
     */
    public function removeCoupon()
    {
        $this->coupon_id = null;
        $this->coupon_code = null;
        $this->discount_amount = 0;
        $this->save();

        return true;
    }

    /**
     * Calculate the grand total
     */
    public function calculateGrandTotal()
    {
        // Calculate grand total based on order items, shipping, and discount
        $itemsTotal = $this->calculateSubtotal();
        $this->subtotal = $itemsTotal;
        
        // Apply discount if coupon exists
        $discountAmount = $this->discount_amount ?? 0;
        
        $this->grand_total = $itemsTotal + $this->shipping_amount - $discountAmount;
        $this->save();
        
        return $this->grand_total;
    }
}
