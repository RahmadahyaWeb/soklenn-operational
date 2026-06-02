<?php

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    use AuthorizesCrud, AuthorizesRequests;

    public ?Expense $expense = null;

    public $expense_category_id;

    public $transaction_date;

    public $amount = 0;

    public $title;

    public $description;

    public function mount(?Expense $expense = null)
    {
        if ($expense && $expense->exists) {

            if ($expense->item_purchase_id) {
                abort(403);
            }

            $this->authorizeUpdate($expense);

            $this->expense = $expense;

            $this->expense_category_id = $expense->expense_category_id;
            $this->transaction_date = $expense->transaction_date?->format('Y-m-d');
            $this->amount = $expense->amount;
            $this->title = $expense->title;
            $this->description = $expense->description;

        } else {

            $this->authorizeStore(Expense::class);

            $this->transaction_date = now()->format('Y-m-d');

        }
    }

    #[Computed()]
    public function expenseCategories()
    {
        return ExpenseCategory::orderBy('name')->get();
    }

    public function save()
    {
        $this->validate([
            'expense_category_id' => [
                'required',
                'exists:expense_categories,id',
            ],

            'transaction_date' => [
                'required',
                'date',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        return $this->transaction(function () {

            $data = [
                'expense_category_id' => $this->expense_category_id,
                'transaction_date' => $this->transaction_date,
                'amount' => $this->amount,
                'title' => $this->title,
                'description' => $this->description,
            ];

            if ($this->expense) {

                if ($this->expense->item_purchase_id) {

                    Flux::toast(
                        heading: 'Warning',
                        text: 'Purchase expense cannot be updated',
                        variant: 'warning'
                    );

                    return;
                }

                $this->authorizeUpdate($this->expense);

                $this->expense->update($data);

                Flux::toast(
                    heading: 'Success',
                    text: 'Expense updated successfully',
                    variant: 'success'
                );

            } else {

                $this->authorizeStore(Expense::class);

                Expense::create($data);

                Flux::toast(
                    heading: 'Success',
                    text: 'Expense created successfully',
                    variant: 'success'
                );

            }

            $this->redirect(route('expenses.index'), navigate: true);

        });
    }
};
