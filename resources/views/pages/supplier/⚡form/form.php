<?php

use App\Models\Supplier;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

new class extends Component
{
    use AuthorizesCrud, AuthorizesRequests;

    public ?Supplier $supplier = null;

    public $name;

    public $phone;

    public $address;

    public function mount(?Supplier $supplier = null)
    {
        if ($supplier && $supplier->exists) {

            $this->authorizeUpdate($supplier);

            $this->supplier = $supplier;

            $this->name = $supplier->name;
            $this->phone = $supplier->phone;
            $this->address = $supplier->address;

        } else {

            $this->authorizeStore(Supplier::class);

        }
    }

    public function save()
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                $this->supplier
                    ? 'unique:suppliers,name,'.$this->supplier->id
                    : 'unique:suppliers,name',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
            ],
        ]);

        return $this->transaction(function () {

            $data = [
                'name' => $this->name,
                'phone' => $this->phone,
                'address' => $this->address,
            ];

            if ($this->supplier) {

                $this->authorizeUpdate($this->supplier);

                $this->supplier->update($data);

                Flux::toast(
                    heading: 'Success',
                    text: 'Supplier updated successfully',
                    variant: 'success'
                );

            } else {

                $this->authorizeStore(Supplier::class);

                Supplier::create($data);

                Flux::toast(
                    heading: 'Success',
                    text: 'Supplier created successfully',
                    variant: 'success'
                );

            }

            $this->redirect(route('suppliers.index'), navigate: true);

        });
    }
};
