<?php

use App\Models\Income;
use App\Models\IncomeCategory;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Title('Incomes')] class extends Component
{
    use AuthorizesCrud;
    use WithoutUrlPagination, WithPagination;

    public $deleteId;

    public $search = '';

    public $income_category_id = '';

    public $start_date = '';

    public $end_date = '';

    public function mount()
    {
        $this->authorizeIndex(Income::class);
    }

    #[Computed()]
    public function incomes()
    {
        return Income::with([
            'category',
            'order.customer',
        ])
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('title', 'like', '%'.$this->search.'%')
                        ->orWhereHas('order', function ($query) {
                            $query->where('invoice_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('order.customer', function ($query) {
                            $query->where('name', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->income_category_id, function ($query) {
                $query->where('income_category_id', $this->income_category_id);
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
    public function incomeCategories()
    {
        return IncomeCategory::orderBy('name')->get();
    }

    #[Computed()]
    public function totalIncome()
    {
        return Income::when($this->search, function ($query) {
            $query->where(function ($query) {
                $query->where('title', 'like', '%'.$this->search.'%')
                    ->orWhereHas('order', function ($query) {
                        $query->where('invoice_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('order.customer', function ($query) {
                        $query->where('name', 'like', '%'.$this->search.'%');
                    });
            });
        })
            ->when($this->income_category_id, function ($query) {
                $query->where('income_category_id', $this->income_category_id);
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
        $income = Income::findOrFail($id);

        $this->authorizeDelete($income);

        $this->deleteId = $id;

        $this->modal('delete-income')->show();
    }

    public function destroy()
    {
        $this->transaction(function () {

            $income = Income::findOrFail($this->deleteId);

            if ($income->order_id) {

                Flux::toast(
                    heading: 'Warning',
                    text: 'Order income cannot be deleted',
                    variant: 'warning'
                );

                return;
            }

            $this->authorizeDelete($income);

            $income->delete();

            Flux::toast(
                heading: 'Success',
                text: 'Income deleted successfully',
                variant: 'success'
            );

            $this->modal('delete-income')->close();

        });
    }
};
