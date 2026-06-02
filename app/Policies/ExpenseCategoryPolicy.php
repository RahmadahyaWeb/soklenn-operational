<?php

namespace App\Policies;

use App\Models\ExpenseCategory;
use App\Models\User;

class ExpenseCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('expense-category.view');
    }

    public function view(User $user, ExpenseCategory $expenseCategory): bool
    {
        return $user->can('expense-category.view');
    }

    public function create(User $user): bool
    {
        return $user->can('expense-category.create');
    }

    public function update(User $user, ExpenseCategory $expenseCategory): bool
    {
        return $user->can('expense-category.update');
    }

    public function delete(User $user, ExpenseCategory $expenseCategory): bool
    {
        return $user->can('expense-category.delete');
    }
}
