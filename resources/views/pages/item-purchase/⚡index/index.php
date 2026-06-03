<?php

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Item;
use App\Models\ItemPurchase;
use App\Models\Supplier;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Title('Item Purchases')] class extends Component
{
    use AuthorizesCrud;
    use WithoutUrlPagination, WithPagination;

    public $deleteId;

    public $supplier_id = '';

    public $item_id = '';

    public $status = '0';

    public $start_date = '';

    public $end_date = '';

    public function mount()
    {
        $this->authorizeIndex(ItemPurchase::class);
    }

    #[Computed()]
    public function itemPurchases()
    {
        return ItemPurchase::with([
            'supplier',
            'item',
        ])
            ->when($this->supplier_id, function ($query) {
                $query->where('supplier_id', $this->supplier_id);
            })
            ->when($this->item_id, function ($query) {
                $query->where('item_id', $this->item_id);
            })
            ->when($this->status !== '', function ($query) {

                $query->where(
                    'is_approved',
                    (bool) $this->status
                );

            })
            ->when($this->start_date, function ($query) {
                $query->whereDate('purchase_date', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($query) {
                $query->whereDate('purchase_date', '<=', $this->end_date);
            })
            ->latest('purchase_date')
            ->paginate(10);
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

    #[Computed()]
    public function totalPurchases()
    {
        return ItemPurchase::when($this->supplier_id, function ($query) {
            $query->where('supplier_id', $this->supplier_id);
        })
            ->when($this->item_id, function ($query) {
                $query->where('item_id', $this->item_id);
            })
            ->when($this->status !== '', function ($query) {

                $query->where(
                    'is_approved',
                    (bool) $this->status
                );

            })
            ->when($this->start_date, function ($query) {
                $query->whereDate('purchase_date', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($query) {
                $query->whereDate('purchase_date', '<=', $this->end_date);
            })
            ->sum('total');
    }

    public function confirmDelete(int $id): void
    {
        $itemPurchase = ItemPurchase::findOrFail($id);

        $this->authorizeDelete($itemPurchase);

        abort_if(
            $itemPurchase->is_approved,
            403,
            'Approved purchase cannot be deleted.'
        );

        $this->deleteId = $id;

        $this->modal('delete-item-purchase')->show();
    }

    public function destroy()
    {
        $this->transaction(function () {

            $itemPurchase = ItemPurchase::findOrFail($this->deleteId);

            $this->authorizeDelete($itemPurchase);

            abort_if(
                $itemPurchase->is_approved,
                403,
                'Approved purchase cannot be deleted.'
            );

            if ($itemPurchase->expense) {
                $itemPurchase->expense->delete();
            }

            $itemPurchase->delete();

            Flux::toast(
                heading: 'Success',
                text: 'Item purchase deleted successfully',
                variant: 'success'
            );

            $this->modal('delete-item-purchase')->close();

        });
    }

    public function approve(int $id)
    {
        return $this->transaction(function () use ($id) {

            $itemPurchase = ItemPurchase::with([
                'item',
            ])->findOrFail($id);

            if ($itemPurchase->is_approved) {
                return;
            }

            $itemPurchase->item->increment(
                'stock',
                $itemPurchase->qty
            );

            $expenseCategory = ExpenseCategory::where('name', 'Item Purchases')
                ->first();

            if ($expenseCategory) {

                Expense::create([
                    'expense_category_id' => $expenseCategory->id,
                    'item_purchase_id' => $itemPurchase->id,
                    'transaction_date' => $itemPurchase->purchase_date,
                    'amount' => $itemPurchase->total,
                    'title' => 'Purchase - '.$itemPurchase->item->name,
                    'description' => $itemPurchase->note,
                ]);

            }

            $itemPurchase->update([
                'is_approved' => true,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            $itemPurchase->refresh();

            Flux::toast(
                heading: 'Success',
                text: 'Item purchase approved successfully',
                variant: 'success'
            );

        });
    }
};
