<?php

use App\Models\Customer;
use App\Models\CustomerMembership;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

new class extends Component
{
    use AuthorizesCrud, AuthorizesRequests;

    public ?Customer $customer = null;

    public $name;

    public $phone;

    public function mount(?Customer $customer = null)
    {
        if ($customer && $customer->exists) {

            $this->authorizeUpdate($customer);

            $this->customer = $customer;

            $this->name = $customer->name;
            $this->phone = $customer->phone;

        } else {

            $this->authorizeStore(Customer::class);

        }
    }

    public function save()
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                $this->customer
                    ? 'unique:customers,name,'.$this->customer->id
                    : 'unique:customers,name',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        return $this->transaction(function () {

            $data = [
                'name' => $this->name,
                'phone' => $this->phone,
            ];

            if ($this->customer) {

                $this->authorizeUpdate($this->customer);

                $this->customer->update($data);

                Flux::toast(
                    heading: 'Success',
                    text: 'Customer updated successfully',
                    variant: 'success'
                );

            } else {

                $this->authorizeStore(Customer::class);

                $customer = Customer::create($data);

                CustomerMembership::create([
                    'customer_id' => $customer->id,
                    'membership_number' => $this->generateMembershipNumber(),
                    'member_since' => now(),
                ]);

                Flux::toast(
                    heading: 'Success',
                    text: 'Customer created successfully',
                    variant: 'success'
                );

            }

            $this->redirect(route('customers.index'), navigate: true);

        });
    }

    protected function generateMembershipNumber(): string
    {
        $lastId = CustomerMembership::max('id') + 1;

        return 'SKL-'.str_pad($lastId, 5, '0', STR_PAD_LEFT);
    }
};
