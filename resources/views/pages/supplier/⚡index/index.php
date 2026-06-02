<?php

use App\Models\Supplier;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Title('Suppliers')] class extends Component
{
    use AuthorizesCrud;
    use WithoutUrlPagination, WithPagination;

    public $deleteId;

    public function mount()
    {
        $this->authorizeIndex(Supplier::class);
    }

    #[Computed()]
    public function suppliers()
    {
        return Supplier::latest()->paginate(10);
    }

    public function confirmDelete(int $id): void
    {
        $supplier = Supplier::findOrFail($id);

        $this->authorizeDelete($supplier);

        $this->deleteId = $id;

        $this->modal('delete-supplier')->show();
    }

    public function destroy()
    {
        $this->transaction(function () {

            $supplier = Supplier::findOrFail($this->deleteId);

            $this->authorizeDelete($supplier);

            $supplier->delete();

            Flux::toast(
                heading: 'Success',
                text: 'Supplier deleted successfully',
                variant: 'success'
            );

            $this->modal('delete-supplier')->close();

        });
    }
};
