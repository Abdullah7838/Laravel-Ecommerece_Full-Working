<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = "order_items";

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_amount',
        'total_amount',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        // When an order item is created, decrease the product stock
        static::created(function ($orderItem) {
            if ($orderItem->product) {
                $orderItem->product->updateStock($orderItem->quantity, 'decrease');
            }
        });

        // When an order item is updated, adjust the product stock
        static::updated(function ($orderItem) {
            if ($orderItem->product && $orderItem->isDirty('quantity')) {
                $originalQuantity = $orderItem->getOriginal('quantity') ?? 0;
                $newQuantity = $orderItem->quantity;
                
                if ($newQuantity > $originalQuantity) {
                    // If quantity increased, decrease stock by the difference
                    $orderItem->product->updateStock($newQuantity - $originalQuantity, 'decrease');
                } elseif ($newQuantity < $originalQuantity) {
                    // If quantity decreased, increase stock by the difference
                    $orderItem->product->updateStock($originalQuantity - $newQuantity, 'increase');
                }
            }
        });

        // When an order item is deleted, increase the product stock
        static::deleted(function ($orderItem) {
            if ($orderItem->product) {
                $orderItem->product->updateStock($orderItem->quantity, 'increase');
            }
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
