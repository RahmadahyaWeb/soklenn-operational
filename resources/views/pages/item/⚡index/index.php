<?php

use App\Models\Category;
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

    public $search = '';

    public $category = '';

    public $status = '';

    public function mount()
    {
        $this->authorizeIndex(Item::class);
    }

    #[Computed()]
    public function categories()
    {
        return Category::orderBy('name')->get();
    }

    #[Computed()]
    public function items()
    {
        return Item::query()
            ->with('category')

            ->when($this->search, function ($query) {

                $query->where(function ($q) {

                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('unit', 'like', '%'.$this->search.'%');

                });

            })

            ->when($this->category, function ($query) {

                $query->where('category_id', $this->category);

            })

            ->when($this->status !== '', function ($query) {

                $query->where('is_active', $this->status);

            })

            ->latest()
            ->paginate(10);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
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
