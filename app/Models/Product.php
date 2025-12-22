<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'barcode',
        'count',
        'purchase_price',
        'selling_price',
    ];

    protected $attributes = [
        'purchase_price' => 0,
        'selling_price' => 0,
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}