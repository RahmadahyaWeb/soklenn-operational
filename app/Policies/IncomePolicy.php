<?php

namespace App\Policies;

use App\Models\Income;
use App\Models\User;

class IncomePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('income.view');
    }

    public function view(User $user, Income $income): bool
    {
        return $user->can('income.view');
    }

    public function create(User $user): bool
    {
        return $user->can('income.create');
    }

    public function update(User $user, Income $income): bool
    {
        return $user->can('income.update');
    }

    public function delete(User $user, Income $income): bool
    {
        return $user->can('income.delete');
    }
}
