<?php

namespace App\Policies;

use App\Models\Stock;
use App\Models\User;

class StockPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Stock $stock): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Stock $stock): bool
    {
        return $user->role === 'super';
    }

    public function delete(User $user, Stock $stock): bool
    {
        return $user->role === 'super';
    }

    public function restore(User $user, Stock $stock): bool
    {
        return $user->role === 'super';
    }

    public function forceDelete(User $user, Stock $stock): bool
    {
        return $user->role === 'super';
    }
}