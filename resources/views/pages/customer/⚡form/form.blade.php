<div class="space-y-6">

    <x-page-header :title="$customer ? 'Edit Customer' : 'Create Customer'" :description="$customer
        ? 'Update customer information and contact details.'
        : 'Create a new customer for shoe cleaning orders.'" />

    <form wire:submit.prevent="save">

        <flux:card class="space-y-6">

            <flux:field>
                <flux:label>Name</flux:label>

                <flux:input wire:model="name" type="text" />

                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Phone</flux:label>

                <flux:input wire:model="phone" type="text" />

                <flux:error name="phone" />
            </flux:field>

            <div class="flex justify-end">

                <flux:button type="submit" variant="primary">
                    {{ $customer ? 'Update' : 'Create' }}
                </flux:button>

            </div>

        </flux:card>

    </form>

</div>
