<?php

namespace App\Policies;

use App\Models\StockAdjustment;
use App\Models\User;

class StockAdjustmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('stock-adjustment.view');
    }

    public function view(User $user, StockAdjustment $stockAdjustment): bool
    {
        return $user->can('stock-adjustment.view');
    }

    public function create(User $user): bool
    {
        return $user->can('stock-adjustment.create');
    }

    public function update(User $user, StockAdjustment $stockAdjustment): bool
    {
        return $user->can('stock-adjustment.update');
    }

    public function delete(User $user, StockAdjustment $stockAdjustment): bool
    {
        return $user->can('stock-adjustment.delete');
    }
}
