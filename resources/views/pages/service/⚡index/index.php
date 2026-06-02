<?php

use App\Models\Service;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Title('Services')] class extends Component
{
    use AuthorizesCrud;
    use WithoutUrlPagination, WithPagination;

    public $deleteId;

    public function mount()
    {
        $this->authorizeIndex(Service::class);
    }

    #[Computed()]
    public function services()
    {
        return Service::latest()->paginate(10);
    }

    public function confirmDelete(int $id): void
    {
        $service = Service::findOrFail($id);

        $this->authorizeDelete($service);

        $this->deleteId = $id;

        $this->modal('delete-service')->show();
    }

    public function destroy()
    {
        $this->transaction(function () {

            $service = Service::findOrFail($this->deleteId);

            $this->authorizeDelete($service);

            $service->delete();

            Flux::toast(
                heading: 'Success',
                text: 'Service deleted successfully',
                variant: 'success'
            );

            $this->modal('delete-service')->close();

        });
    }
};
