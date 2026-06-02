<?php

use App\Models\Service;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

new class extends Component
{
    use AuthorizesCrud, AuthorizesRequests;

    public ?Service $service = null;

    public $name;

    public $price = 0;

    public $description;

    public $is_active = true;

    public function mount(?Service $service = null)
    {
        if ($service && $service->exists) {

            $this->authorizeUpdate($service);

            $this->service = $service;

            $this->name = $service->name;
            $this->price = $service->price;
            $this->description = $service->description;
            $this->is_active = $service->is_active;

        } else {

            $this->authorizeStore(Service::class);

        }
    }

    public function save()
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                $this->service
                    ? 'unique:services,name,'.$this->service->id
                    : 'unique:services,name',
            ],

            'price' => [
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
                'name' => $this->name,
                'price' => $this->price,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ];

            if ($this->service) {

                $this->authorizeUpdate($this->service);

                $this->service->update($data);

                Flux::toast(
                    heading: 'Success',
                    text: 'Service updated successfully',
                    variant: 'success'
                );

            } else {

                $this->authorizeStore(Service::class);

                Service::create($data);

                Flux::toast(
                    heading: 'Success',
                    text: 'Service created successfully',
                    variant: 'success'
                );

            }

            $this->redirect(route('services.index'), navigate: true);

        });
    }
};
