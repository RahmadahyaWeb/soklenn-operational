<?php

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

        $this->deleteId = $id;

        $this->modal('delete-item-purchase')->show();
    }

    public function destroy()
    {
        $this->transaction(function () {

            $itemPurchase = ItemPurchase::with('item')
                ->findOrFail($this->deleteId);

            $this->authorizeDelete($itemPurchase);

            $itemPurchase->item->decrement(
                'stock',
                $itemPurchase->qty
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
};
