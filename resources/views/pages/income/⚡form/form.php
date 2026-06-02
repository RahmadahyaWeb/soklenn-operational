<?php

use App\Models\Income;
use App\Models\IncomeCategory;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    use AuthorizesCrud, AuthorizesRequests;

    public ?Income $income = null;

    public $income_category_id;

    public $transaction_date;

    public $amount = 0;

    public $title;

    public $description;

    public function mount(?Income $income = null)
    {
        if ($income?->order_id) {

            abort(403);

        }

        if ($income && $income->exists) {

            $this->authorizeUpdate($income);

            $this->income = $income;

            $this->income_category_id = $income->income_category_id;
            $this->transaction_date = $income->transaction_date?->format('Y-m-d');
            $this->amount = $income->amount;
            $this->title = $income->title;
            $this->description = $income->description;

        } else {

            $this->authorizeStore(Income::class);

            $this->transaction_date = now()->format('Y-m-d');

        }
    }

    #[Computed()]
    public function incomeCategories()
    {
        return IncomeCategory::where('name', '!=', 'Order Income')
            ->orderBy('name')
            ->get();
    }

    public function save()
    {
        $this->validate([
            'income_category_id' => [
                'required',
                'exists:income_categories,id',
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
                'income_category_id' => $this->income_category_id,
                'transaction_date' => $this->transaction_date,
                'amount' => $this->amount,
                'title' => $this->title,
                'description' => $this->description,
            ];

            if ($this->income) {

                $this->authorizeUpdate($this->income);

                $this->income->update($data);

                Flux::toast(
                    heading: 'Success',
                    text: 'Income updated successfully',
                    variant: 'success'
                );

            } else {

                $this->authorizeStore(Income::class);

                Income::create($data);

                Flux::toast(
                    heading: 'Success',
                    text: 'Income created successfully',
                    variant: 'success'
                );

            }

            $this->redirect(route('incomes.index'), navigate: true);

        });
    }
};
