<?php

use App\Models\Item;
use App\Models\StockAdjustment;
use App\Traits\AuthorizesCrud;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Title('Stock Adjustments')] class extends Component
{
    use AuthorizesCrud;
    use WithoutUrlPagination, WithPagination;

    public $item_id = '';

    public $type = '';

    public $start_date = '';

    public $end_date = '';

    public function mount()
    {
        $this->authorizeIndex(StockAdjustment::class);
    }

    #[Computed()]
    public function stockAdjustments()
    {
        return StockAdjustment::with('item')
            ->when($this->item_id, function ($query) {
                $query->where('item_id', $this->item_id);
            })
            ->when($this->type, function ($query) {
                $query->where('type', $this->type);
            })
            ->when($this->start_date, function ($query) {
                $query->whereDate('adjustment_date', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($query) {
                $query->whereDate('adjustment_date', '<=', $this->end_date);
            })
            ->latest('adjustment_date')
            ->paginate(10);
    }

    #[Computed()]
    public function items()
    {
        return Item::orderBy('name')->get();
    }
};
