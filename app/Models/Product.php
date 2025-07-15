<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'images',
        'description',
        'short_description',
        'price',
        'sku',
        'is_active',
        'is_featured',
        'in_stock',
        'on_sale',
        'stock_quantity',
        'low_stock_threshold',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    
    protected $casts = [
        'images' => 'array',
    ];
    
    /**
     * Get the image URLs with absolute paths
     *
     * @return array
     */
    public function getImageUrlsAttribute()
    {
        if (empty($this->images)) {
            return [];
        }
        
        return array_map(function ($image) {
            return asset('storage/' . $image);
        }, $this->images);
    }

    /**
     * Get the category that owns the product.
     */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the brand that owns the product.
     */

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the Order Items that owns the product.
     */

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Check if product is low on stock
     */
    public function isLowStock(): bool
    {
        if (!$this->in_stock || $this->low_stock_threshold === null) {
            return false;
        }

        return $this->stock_quantity <= $this->low_stock_threshold;
    }

    /**
     * Update stock quantity
     */
    public function updateStock(int $quantity, string $operation = 'decrease'): void
    {
        if ($operation === 'decrease') {
            $this->stock_quantity = max(0, $this->stock_quantity - $quantity);
            
            // Update in_stock status if quantity reaches 0
            if ($this->stock_quantity === 0) {
                $this->in_stock = false;
            }
        } else {
            $this->stock_quantity += $quantity;
            
            // Update in_stock status if product was out of stock
            if (!$this->in_stock && $this->stock_quantity > 0) {
                $this->in_stock = true;
            }
        }

        $this->save();
    }
}
