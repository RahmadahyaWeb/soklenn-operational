<?php

namespace App\Policies;

use App\Models\ItemPurchase;
use App\Models\User;

class ItemPurchasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('item-purchase.view');
    }

    public function view(User $user, ItemPurchase $itemPurchase): bool
    {
        return $user->can('item-purchase.view');
    }

    public function create(User $user): bool
    {
        return $user->can('item-purchase.create');
    }

    public function update(User $user, ItemPurchase $itemPurchase): bool
    {
        return $user->can('item-purchase.update');
    }

    public function delete(User $user, ItemPurchase $itemPurchase): bool
    {
        return $user->can('item-purchase.delete');
    }
}
