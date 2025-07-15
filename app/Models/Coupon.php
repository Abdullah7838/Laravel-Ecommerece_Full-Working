<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = "coupons";

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_uses',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Check if coupon is valid
     */
    public function isValid($orderAmount = 0)
    {
        // Check if coupon is active
        if (!$this->is_active) {
            return false;
        }

        // Check if coupon has reached max uses
        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return false;
        }

        // Check if coupon has started
        if ($this->starts_at !== null && now() < $this->starts_at) {
            return false;
        }

        // Check if coupon has expired
        if ($this->expires_at !== null && now() > $this->expires_at) {
            return false;
        }

        // Check if order amount meets minimum requirement
        if ($this->min_order_amount !== null && $orderAmount < $this->min_order_amount) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount($orderAmount)
    {
        if (!$this->isValid($orderAmount)) {
            return 0;
        }

        if ($this->type === 'percentage') {
            return ($orderAmount * $this->value) / 100;
        }

        if ($this->type === 'fixed') {
            return min($this->value, $orderAmount); // Don't allow discount greater than order amount
        }

        return 0;
    }

    /**
     * Increment used count
     */
    public function incrementUsedCount()
    {
        $this->used_count++;
        $this->save();
    }
}