<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProduct extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'count',
        'discount',
        'price_summ',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($orderProduct) {
            $product = Product::find($orderProduct->product_id);
            
            if ($product) {
                $product->decrement('count', $orderProduct->count);
            }
        });

        static::updated(function ($orderProduct) {
            $product = Product::find($orderProduct->product_id);
            
            if ($product && $orderProduct->isDirty('count')) {
                $oldCount = $orderProduct->getOriginal('count');
                $newCount = $orderProduct->count;
                $difference = $newCount - $oldCount;
                
                $product->decrement('count', $difference);
            }
        });

        static::deleted(function ($orderProduct) {
            $product = Product::find($orderProduct->product_id);
            
            if ($product) {
                $product->increment('count', $orderProduct->count);
            }
        });
    }
}