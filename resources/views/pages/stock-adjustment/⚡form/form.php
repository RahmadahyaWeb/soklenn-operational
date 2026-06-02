<?php

use App\Models\Item;
use App\Models\StockAdjustment;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    use AuthorizesCrud, AuthorizesRequests;

    public $item_id;

    public $type = 'in';

    public $qty = 1;

    public $note;

    public $adjustment_date;

    public $current_stock = 0;

    public $after_stock = 0;

    public function mount()
    {
        $this->authorizeStore(StockAdjustment::class);

        $this->adjustment_date = now()->format('Y-m-d');

        $this->calculateStockPreview();
    }

    #[Computed()]
    public function items()
    {
        return Item::orderBy('name')->get();
    }

    public function updatedItemId()
    {
        $this->calculateStockPreview();
    }

    public function updatedType()
    {
        $this->calculateStockPreview();
    }

    public function updatedQty()
    {
        $this->calculateStockPreview();
    }

    public function calculateStockPreview()
    {
        $item = Item::find($this->item_id);

        $this->current_stock = $item?->stock ?? 0;

        if ($this->type === 'in') {

            $this->after_stock = $this->current_stock + (int) $this->qty;

        } else {

            $this->after_stock = $this->current_stock - (int) $this->qty;

        }
    }

    public function save()
    {
        $this->validate([
            'item_id' => [
                'required',
                'exists:items,id',
            ],

            'type' => [
                'required',
                'in:in,out',
            ],

            'qty' => [
                'required',
                'integer',
                'min:1',
            ],

            'adjustment_date' => [
                'required',
                'date',
            ],

            'note' => [
                'nullable',
                'string',
            ],
        ]);

        return $this->transaction(function () {

            $item = Item::findOrFail($this->item_id);

            $beforeStock = $item->stock;

            if (
                $this->type === 'out'
                && $this->qty > $beforeStock
            ) {

                Flux::toast(
                    heading: 'Warning',
                    text: 'Adjustment quantity exceeds current stock',
                    variant: 'warning'
                );

                return;
            }

            if ($this->type === 'in') {

                $afterStock = $beforeStock + $this->qty;

            } else {

                $afterStock = $beforeStock - $this->qty;

            }

            $item->update([
                'stock' => $afterStock,
            ]);

            StockAdjustment::create([
                'item_id' => $this->item_id,
                'type' => $this->type,
                'qty' => $this->qty,
                'before_stock' => $beforeStock,
                'after_stock' => $afterStock,
                'note' => $this->note,
                'adjustment_date' => $this->adjustment_date,
            ]);

            Flux::toast(
                heading: 'Success',
                text: 'Stock adjustment created successfully',
                variant: 'success'
            );

            $this->redirect(
                route('stock-adjustments.index'),
                navigate: true
            );

        });
    }
};
