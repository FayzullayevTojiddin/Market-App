<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockProduct extends Model
{
    protected $fillable = [
        'stock_id',
        'product_id',
        'count',
        'purchase_price',
        'selling_price',
    ];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($stockProduct) {
            self::updateProductCount($stockProduct->product_id);
        });
        static::updated(function ($stockProduct) {
            self::updateProductCount($stockProduct->product_id);
            
            if ($stockProduct->isDirty('product_id')) {
                $originalProductId = $stockProduct->getOriginal('product_id');
                self::updateProductCount($originalProductId);
            }
        });
        static::deleted(function ($stockProduct) {
            self::updateProductCount($stockProduct->product_id);
        });
    }

    protected static function updateProductCount(int $productId): void
    {
        $product = Product::find($productId);
        if (!$product) {
            return;
        }
        $totalCount = self::where('product_id', $productId)->sum('count');        
        $product->update(['count' => $totalCount]);
    }
}