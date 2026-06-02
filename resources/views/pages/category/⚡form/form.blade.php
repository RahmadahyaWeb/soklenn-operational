<div class="space-y-6">

    <x-page-header :title="$category ? 'Edit Category' : 'Create Category'" :description="$category
        ? 'Update category information for inventory management.'
        : 'Create a new category for operational or non operational items.'" />

    <form wire:submit.prevent="save">

        <flux:card class="space-y-6">

            <flux:field>
                <flux:label>Name</flux:label>

                <flux:input wire:model="name" type="text" />

                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Type</flux:label>

                <flux:select wire:model="type">

                    <flux:select.option value="operational">
                        Operational
                    </flux:select.option>

                    <flux:select.option value="non_operational">
                        Non Operational
                    </flux:select.option>

                </flux:select>

                <flux:error name="type" />
            </flux:field>

            <div class="flex justify-end">
                <flux:button type="submit" variant="primary">
                    {{ $category ? 'Update' : 'Create' }}
                </flux:button>
            </div>

        </flux:card>

    </form>

</div>
