<?php

use App\Models\ExpenseCategory;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Title('Expense Categories')] class extends Component
{
    use AuthorizesCrud;
    use WithoutUrlPagination, WithPagination;

    public $deleteId;

    public function mount()
    {
        $this->authorizeIndex(ExpenseCategory::class);
    }

    #[Computed()]
    public function expenseCategories()
    {
        return ExpenseCategory::latest()->paginate(10);
    }

    public function confirmDelete(int $id): void
    {
        $expenseCategory = ExpenseCategory::findOrFail($id);

        $this->authorizeDelete($expenseCategory);

        $this->deleteId = $id;

        $this->modal('delete-expense-category')->show();
    }

    public function destroy()
    {
        $this->transaction(function () {

            $expenseCategory = ExpenseCategory::findOrFail($this->deleteId);

            $this->authorizeDelete($expenseCategory);

            $expenseCategory->delete();

            Flux::toast(
                heading: 'Success',
                text: 'Expense category deleted successfully',
                variant: 'success'
            );

            $this->modal('delete-expense-category')->close();

        });
    }
};
