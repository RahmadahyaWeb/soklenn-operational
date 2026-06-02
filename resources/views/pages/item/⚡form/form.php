<?php

use App\Models\Category;
use App\Models\Item;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    use AuthorizesCrud, AuthorizesRequests;

    public ?Item $item = null;

    public $category_id;

    public $name;

    public $unit;

    public $minimum_stock = 0;

    public $buy_price = 0;

    public $description;

    public $is_active = true;

    public function mount(?Item $item = null)
    {
        if ($item && $item->exists) {

            $this->authorizeUpdate($item);

            $this->item = $item;

            $this->category_id = $item->category_id;
            $this->name = $item->name;
            $this->unit = $item->unit;
            $this->minimum_stock = $item->minimum_stock;
            $this->buy_price = $item->buy_price;
            $this->description = $item->description;
            $this->is_active = $item->is_active;

        } else {

            $this->authorizeStore(Item::class);

        }
    }

    #[Computed()]
    public function categories()
    {
        return Category::orderBy('name')->get();
    }

    public function save()
    {
        $this->validate([
            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
                $this->item
                    ? 'unique:items,name,'.$this->item->id
                    : 'unique:items,name',
            ],

            'unit' => [
                'nullable',
                'string',
                'max:100',
            ],

            'minimum_stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'buy_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        return $this->transaction(function () {

            $data = [
                'category_id' => $this->category_id,
                'name' => $this->name,
                'unit' => $this->unit,
                'minimum_stock' => $this->minimum_stock,
                'buy_price' => $this->buy_price,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ];

            if ($this->item) {

                $this->authorizeUpdate($this->item);

                $this->item->update($data);

                Flux::toast(
                    heading: 'Success',
                    text: 'Item updated successfully',
                    variant: 'success'
                );

            } else {

                $this->authorizeStore(Item::class);

                Item::create([
                    ...$data,
                    'stock' => 0,
                ]);

                Flux::toast(
                    heading: 'Success',
                    text: 'Item created successfully',
                    variant: 'success'
                );

            }

            $this->redirect(route('items.index'), navigate: true);

        });
    }
};
