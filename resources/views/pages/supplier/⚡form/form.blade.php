<div class="space-y-6">

    <x-page-header :title="$supplier ? 'Edit Supplier' : 'Create Supplier'" :description="$supplier
        ? 'Update supplier information for inventory purchasing.'
        : 'Create a new supplier for operational inventory management.'" />

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

            <flux:field>
                <flux:label>Address</flux:label>

                <flux:textarea wire:model="address" rows="4" />

                <flux:error name="address" />
            </flux:field>

            <div class="flex justify-end">

                <flux:button type="submit" variant="primary">
                    {{ $supplier ? 'Update' : 'Create' }}
                </flux:button>

            </div>

        </flux:card>

    </form>

</div>
