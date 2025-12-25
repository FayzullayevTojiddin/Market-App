<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    protected $fillable = [
        'full_name',
        'phone_number',
    ];

    protected $appends = ['remaining_debt'];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function debtTransactions(): HasMany
    {
        return $this->hasMany(DebtTransaction::class);
    }

    protected function remainingDebt(): Attribute
    {
        return Attribute::make(
            get: fn () => (int) $this->debtTransactions()
                ->selectRaw("
                    COALESCE(
                        SUM(CASE WHEN type = 'increase' THEN amount ELSE 0 END) -
                        SUM(CASE WHEN type = 'decrease' THEN amount ELSE 0 END),
                    0) AS remaining_debt
                ")
                ->value('remaining_debt')
        );
    }
}