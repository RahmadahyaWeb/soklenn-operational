<?php

use App\Models\Item;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Title('Items')] class extends Component
{
    use AuthorizesCrud;
    use WithoutUrlPagination, WithPagination;

    public $deleteId;

    public function mount()
    {
        $this->authorizeIndex(Item::class);
    }

    #[Computed()]
    public function items()
    {
        return Item::with('category')
            ->latest()
            ->paginate(10);
    }

    public function confirmDelete(int $id): void
    {
        $item = Item::findOrFail($id);

        $this->authorizeDelete($item);

        $this->deleteId = $id;

        $this->modal('delete-item')->show();
    }

    public function destroy()
    {
        $this->transaction(function () {

            $item = Item::findOrFail($this->deleteId);

            $this->authorizeDelete($item);

            $item->delete();

            Flux::toast(
                heading: 'Success',
                text: 'Item deleted successfully',
                variant: 'success'
            );

            $this->modal('delete-item')->close();

        });
    }
};
