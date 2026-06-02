<div class="space-y-6">

    <x-page-header :title="$item ? 'Edit Item' : 'Create Item'" :description="$item
        ? 'Update inventory item information.'
        : 'Create a new inventory item for operational or non operational usage.'" />

    <form wire:submit.prevent="save">

        <flux:card class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <flux:field>
                    <flux:label>Category</flux:label>

                    <flux:select wire:model="category_id">

                        <flux:select.option value="">
                            Select Category
                        </flux:select.option>

                        @foreach ($this->categories as $category)
                            <flux:select.option :value="$category->id">
                                {{ $category->name }}
                            </flux:select.option>
                        @endforeach

                    </flux:select>

                    <flux:error name="category_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Name</flux:label>

                    <flux:input wire:model="name" type="text" />

                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label>Unit</flux:label>

                    <flux:input wire:model="unit" type="text" placeholder="pcs, bottle, liter" />

                    <flux:error name="unit" />
                </flux:field>

                <flux:field>
                    <flux:label>Stock</flux:label>

                    <flux:input wire:model="stock" type="number" min="0" />

                    <flux:error name="stock" />
                </flux:field>

                <flux:field>
                    <flux:label>Buy Price</flux:label>

                    <flux:input wire:model="buy_price" type="number" min="0" />

                    <flux:error name="buy_price" />
                </flux:field>

                <flux:field class="flex items-center justify-between">

                    <div>
                        <flux:label>Active Status</flux:label>

                        <flux:description>
                            Enable or disable this inventory item.
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
                    {{ $item ? 'Update' : 'Create' }}
                </flux:button>

            </div>

        </flux:card>

    </form>

</div>
