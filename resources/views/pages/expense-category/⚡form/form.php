<?php

use App\Models\ExpenseCategory;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

new class extends Component
{
    use AuthorizesCrud, AuthorizesRequests;

    public ?ExpenseCategory $expenseCategory = null;

    public $name;

    public function mount(?ExpenseCategory $expenseCategory = null)
    {
        if ($expenseCategory && $expenseCategory->exists) {

            $this->authorizeUpdate($expenseCategory);

            $this->expenseCategory = $expenseCategory;

            $this->name = $expenseCategory->name;

        } else {

            $this->authorizeStore(ExpenseCategory::class);

        }
    }

    public function save()
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                $this->expenseCategory
                    ? 'unique:expense_categories,name,'.$this->expenseCategory->id
                    : 'unique:expense_categories,name',
            ],
        ]);

        return $this->transaction(function () {

            $data = [
                'name' => $this->name,
            ];

            if ($this->expenseCategory) {

                $this->authorizeUpdate($this->expenseCategory);

                $this->expenseCategory->update($data);

                Flux::toast(
                    heading: 'Success',
                    text: 'Expense category updated successfully',
                    variant: 'success'
                );

            } else {

                $this->authorizeStore(ExpenseCategory::class);

                ExpenseCategory::create($data);

                Flux::toast(
                    heading: 'Success',
                    text: 'Expense category created successfully',
                    variant: 'success'
                );

            }

            $this->redirect(route('expense-categories.index'), navigate: true);

        });
    }
};
