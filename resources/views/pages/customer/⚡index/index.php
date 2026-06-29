<?php

use App\Models\Customer;
use App\Models\MembershipReward;
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

    public string $search = '';

    public string $tier = '';

    public string $reward = '';

    public string $stamp = '';

    public function mount()
    {
        $this->authorizeIndex(Customer::class);
    }

    #[Computed]
    public function customers()
    {
        return Customer::with([
            'membership.rewardClaims',
        ])
            ->when($this->search, function ($query) {

                $query->where(function ($query) {

                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%")
                        ->orWhereHas('membership', function ($query) {

                            $query->where(
                                'member_code',
                                'like',
                                "%{$this->search}%"
                            );

                        });

                });

            })

            ->when($this->tier, function ($query) {

                $query->whereHas('membership', function ($query) {

                    $query->where('tier', $this->tier);

                });

            })

            ->when($this->reward === 'available', function ($query) {

                $query->whereHas('membership.rewardClaims', function ($query) {

                    $query->whereNull('used_at');

                });

            })

            ->when($this->reward === 'empty', function ($query) {

                $query->whereDoesntHave('membership.rewardClaims', function ($query) {

                    $query->whereNull('used_at');

                });

            })

            ->when($this->stamp, function ($query) {

                $query->whereHas('membership', function ($query) {

                    match ($this->stamp) {

                        '0' => $query->where('stamp', 0),

                        '1-4' => $query->whereBetween('stamp', [1, 4]),

                        '5-9' => $query->whereBetween('stamp', [5, 9]),

                        '10-14' => $query->whereBetween('stamp', [10, 14]),

                        '15+' => $query->where('stamp', '>=', 15),

                    };

                });

            })

            ->latest()
            ->paginate(10);
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
            'membership.rewardClaims.order',
        ])->findOrFail($id);

        $this->modal('customer-membership')->show();
    }

    public function getNextRewardProperty()
    {
        if (! $this->selectedCustomer?->membership) {
            return null;
        }

        return MembershipReward::query()
            ->where('is_active', true)
            ->where(
                'required_stamp',
                '>',
                $this->selectedCustomer->membership->stamp
            )
            ->orderBy('required_stamp')
            ->first();
    }

    public function copyMembership(int $customerId): void
    {
        $customer = Customer::with('membership')->findOrFail($customerId);

        $this->js('
        navigator.clipboard.writeText('.json_encode($customer->membership->shareText()).");

        \$flux.toast({
            heading: 'Success',
            text: 'Membership info copied successfully',
            variant: 'success'
        });
    ");
    }
};
