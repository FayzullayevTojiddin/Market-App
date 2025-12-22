<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dealer extends Model
{
    protected $fillable = [
        'full_name',
        'phone_number',
        'email',
        'notes',
    ];

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}