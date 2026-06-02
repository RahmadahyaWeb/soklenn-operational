<div class="space-y-6">

    <x-page-header :title="$service ? 'Edit Service' : 'Create Service'" :description="$service
        ? 'Update service information and pricing.'
        : 'Create a new shoe cleaning service for customer orders.'" />

    <form wire:submit.prevent="save">

        <flux:card class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <flux:field>
                    <flux:label>Name</flux:label>

                    <flux:input wire:model="name" type="text" />

                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label>Price</flux:label>

                    <flux:input wire:model="price" type="number" min="0" />

                    <flux:error name="price" />
                </flux:field>

                <flux:field class="md:col-span-2 flex items-center justify-between">

                    <div>
                        <flux:label>Active Status</flux:label>

                        <flux:description>
                            Enable or disable this service.
                        </flux:description>
                    </div>

                    <flux:switch wire:model="is_active" />

                </flux:field>

            </div>

            <flux:field>
                <flux:label>Description</flux:label>

                <flux:textarea wire:model="description" rows="4" />

                <flux:error name="description" />
            </flux:field>

            <div class="flex justify-end">

                <flux:button type="submit" variant="primary">
                    {{ $service ? 'Update' : 'Create' }}
                </flux:button>

            </div>

        </flux:card>

    </form>

</div>
