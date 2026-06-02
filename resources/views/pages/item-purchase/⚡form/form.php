<?php

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Item;
use App\Models\ItemPurchase;
use App\Models\Supplier;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    use AuthorizesCrud, AuthorizesRequests;

    public ?ItemPurchase $itemPurchase = null;

    public $supplier_id;

    public $purchase_date;

    public $note;

    public $item_id;

    public $qty = 1;

    public $price = 0;

    public $total = 0;

    public function mount(?ItemPurchase $itemPurchase = null)
    {
        if ($itemPurchase && $itemPurchase->exists) {

            $this->authorizeUpdate($itemPurchase);

            $this->itemPurchase = $itemPurchase;

            $this->supplier_id = $itemPurchase->supplier_id;
            $this->purchase_date = $itemPurchase->purchase_date?->format('Y-m-d');
            $this->note = $itemPurchase->note;

            $this->item_id = $itemPurchase->item_id;
            $this->qty = $itemPurchase->qty;
            $this->price = $itemPurchase->price;
            $this->total = $itemPurchase->total;

        } else {

            $this->authorizeStore(ItemPurchase::class);

            $this->purchase_date = now()->format('Y-m-d');

        }

        $this->calculateTotal();
    }

    #[Computed()]
    public function suppliers()
    {
        return Supplier::orderBy('name')->get();
    }

    #[Computed()]
    public function items()
    {
        return Item::orderBy('name')->get();
    }

    public function updatedQty()
    {
        $this->calculateTotal();
    }

    public function updatedPrice()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = (int) $this->qty * (float) $this->price;
    }

    public function save()
    {
        $this->validate([
            'supplier_id' => [
                'required',
                'exists:suppliers,id',
            ],

            'purchase_date' => [
                'required',
                'date',
            ],

            'item_id' => [
                'required',
                'exists:items,id',
            ],

            'qty' => [
                'required',
                'integer',
                'min:1',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'note' => [
                'nullable',
                'string',
            ],
        ]);

        return $this->transaction(function () {

            $item = Item::findOrFail($this->item_id);

            $data = [
                'supplier_id' => $this->supplier_id,
                'purchase_date' => $this->purchase_date,
                'item_id' => $this->item_id,
                'qty' => $this->qty,
                'price' => $this->price,
                'total' => $this->total,
                'note' => $this->note,
            ];

            if ($this->itemPurchase) {

                $this->authorizeUpdate($this->itemPurchase);

                $oldQty = $this->itemPurchase->qty;

                $this->itemPurchase->update($data);

                $difference = $this->qty - $oldQty;

                $item->increment('stock', $difference);

                if ($this->itemPurchase->expense) {

                    $this->itemPurchase->expense->update([
                        'transaction_date' => $this->purchase_date,
                        'amount' => $this->total,
                        'title' => 'Purchase - '.$item->name,
                        'description' => $this->note,
                    ]);

                }

                Flux::toast(
                    heading: 'Success',
                    text: 'Item purchase updated successfully',
                    variant: 'success'
                );

            } else {

                $this->authorizeStore(ItemPurchase::class);

                $purchase = ItemPurchase::create($data);

                $item->increment('stock', $this->qty);

                $expenseCategory = ExpenseCategory::where('name', 'Operational')
                    ->first();

                if ($expenseCategory) {

                    Expense::create([
                        'expense_category_id' => $expenseCategory->id,
                        'item_purchase_id' => $purchase->id,
                        'transaction_date' => $this->purchase_date,
                        'amount' => $this->total,
                        'title' => 'Purchase - '.$item->name,
                        'description' => $this->note,
                    ]);

                }

                Flux::toast(
                    heading: 'Success',
                    text: 'Item purchase created successfully',
                    variant: 'success'
                );

            }

            $this->redirect(route('item-purchases.index'), navigate: true);

        });
    }
};
