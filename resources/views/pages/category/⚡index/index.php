<?php

use App\Models\Category;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Title('Categories')] class extends Component
{
    use AuthorizesCrud;
    use WithoutUrlPagination, WithPagination;

    public $deleteId;

    public function mount()
    {
        $this->authorizeIndex(Category::class);
    }

    #[Computed()]
    public function categories()
    {
        return Category::latest()->paginate(10);
    }

    public function confirmDelete(int $id): void
    {
        $category = Category::findOrFail($id);

        $this->authorizeDelete($category);

        $this->deleteId = $id;

        $this->modal('delete-category')->show();
    }

    public function destroy()
    {
        $this->transaction(function () {

            $category = Category::findOrFail($this->deleteId);

            $this->authorizeDelete($category);

            $category->delete();

            Flux::toast(
                heading: 'Success',
                text: 'Category deleted successfully',
                variant: 'success'
            );

            $this->modal('delete-category')->close();

        });
    }
};
