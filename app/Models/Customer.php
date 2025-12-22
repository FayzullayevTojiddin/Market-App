<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'full_name',
        'phone_number',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function debtTransactions(): HasMany
    {
        return $this->hasMany(DebtTransaction::class);
    }
}