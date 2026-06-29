<?php

use App\Exports\ExpenseExport;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

new #[Title('Expenses')] class extends Component
{
    use AuthorizesCrud;
    use WithoutUrlPagination, WithPagination;

    public $deleteId;

    public $search = '';

    public $expense_category_id = '';

    public $start_date = '';

    public $end_date = '';

    public function mount()
    {
        $this->authorizeIndex(Expense::class);
    }

    #[Computed()]
    public function expenses()
    {
        return Expense::with([
            'category',
            'itemPurchase.supplier',
        ])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%'.$this->search.'%');
            })
            ->when($this->expense_category_id, function ($query) {
                $query->where('expense_category_id', $this->expense_category_id);
            })
            ->when($this->start_date, function ($query) {
                $query->whereDate('transaction_date', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($query) {
                $query->whereDate('transaction_date', '<=', $this->end_date);
            })
            ->latest('transaction_date')
            ->paginate(10);
    }

    #[Computed()]
    public function expenseCategories()
    {
        return ExpenseCategory::orderBy('name')->get();
    }

    #[Computed()]
    public function totalExpense()
    {
        return Expense::when($this->search, function ($query) {
            $query->where('title', 'like', '%'.$this->search.'%');
        })
            ->when($this->expense_category_id, function ($query) {
                $query->where('expense_category_id', $this->expense_category_id);
            })
            ->when($this->start_date, function ($query) {
                $query->whereDate('transaction_date', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($query) {
                $query->whereDate('transaction_date', '<=', $this->end_date);
            })
            ->sum('amount');
    }

    public function confirmDelete(int $id): void
    {
        $expense = Expense::findOrFail($id);

        $this->authorizeDelete($expense);

        if ($expense->item_purchase_id) {

            Flux::toast(
                heading: 'Warning',
                text: 'Purchase expense cannot be deleted',
                variant: 'warning'
            );

            return;
        }

        $this->deleteId = $id;

        $this->modal('delete-expense')->show();
    }

    public function destroy()
    {
        $this->transaction(function () {

            $expense = Expense::findOrFail($this->deleteId);

            $this->authorizeDelete($expense);

            if ($expense->item_purchase_id) {

                Flux::toast(
                    heading: 'Warning',
                    text: 'Purchase expense cannot be deleted',
                    variant: 'warning'
                );

                return;
            }

            $expense->delete();

            Flux::toast(
                heading: 'Success',
                text: 'Expense deleted successfully',
                variant: 'success'
            );

            $this->modal('delete-expense')->close();

        });
    }

    public function export()
    {
        return Excel::download(
            new ExpenseExport(
                $this->search,
                $this->expense_category_id,
                $this->start_date,
                $this->end_date
            ),
            'expenses-'.now()->format('YmdHis').'.xlsx'
        );
    }
};
