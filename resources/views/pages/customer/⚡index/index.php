<?php

use App\Models\Customer;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Title('Customers')] class extends Component
{
    use AuthorizesCrud;
    use WithoutUrlPagination, WithPagination;

    public $deleteId;

    public ?Customer $selectedCustomer = null;

    public function mount()
    {
        $this->authorizeIndex(Customer::class);
    }

    #[Computed()]
    public function customers()
    {
        return Customer::latest()->paginate(10);
    }

    public function confirmDelete(int $id): void
    {
        $customer = Customer::findOrFail($id);

        $this->authorizeDelete($customer);

        $this->deleteId = $id;

        $this->modal('delete-customer')->show();
    }

    public function destroy()
    {
        $this->transaction(function () {

            $customer = Customer::findOrFail($this->deleteId);

            $this->authorizeDelete($customer);

            $customer->delete();

            Flux::toast(
                heading: 'Success',
                text: 'Customer deleted successfully',
                variant: 'success'
            );

            $this->modal('delete-customer')->close();

        });
    }

    public function viewMembership(int $id): void
    {
        $this->selectedCustomer = Customer::with([
            'membership.rewardClaims.reward',
        ])->findOrFail($id);

        $this->modal('customer-membership')->show();
    }
};
