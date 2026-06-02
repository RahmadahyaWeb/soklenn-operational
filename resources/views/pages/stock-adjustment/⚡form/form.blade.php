<div class="space-y-6">

    <x-page-header title="Create Stock Adjustment"
        description="Adjust item stock manually for correction, damage, loss, or stock opname." />

    <form wire:submit.prevent="save">

        <flux:card class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <flux:field>
                    <flux:label>Item</flux:label>

                    <flux:select wire:model.live="item_id">

                        <flux:select.option value="">
                            Select Item
                        </flux:select.option>

                        @foreach ($this->items as $item)
                            <flux:select.option :value="$item->id">
                                {{ $item->name }}
                            </flux:select.option>
                        @endforeach

                    </flux:select>

                    <flux:error name="item_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Type</flux:label>

                    <flux:select wire:model.live="type">

                        <flux:select.option value="in">
                            IN
                        </flux:select.option>

                        <flux:select.option value="out">
                            OUT
                        </flux:select.option>

                    </flux:select>

                    <flux:error name="type" />
                </flux:field>

                <flux:field>
                    <flux:label>Qty</flux:label>

                    <flux:input wire:model.live="qty" type="number" min="1" />

                    <flux:error name="qty" />
                </flux:field>

                <flux:field>
                    <flux:label>Adjustment Date</flux:label>

                    <flux:input wire:model="adjustment_date" type="date" />

                    <flux:error name="adjustment_date" />
                </flux:field>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <flux:card>

                    <div class="space-y-2">

                        <div class="text-sm text-zinc-500">
                            Current Stock
                        </div>

                        <div class="text-3xl font-bold">
                            {{ $current_stock }}
                        </div>

                    </div>

                </flux:card>

                <flux:card>

                    <div class="space-y-2">

                        <div class="text-sm text-zinc-500">
                            After Adjustment
                        </div>

                        <div class="text-3xl font-bold">
                            {{ $after_stock }}
                        </div>

                    </div>

                </flux:card>

            </div>

            <flux:field>
                <flux:label>Note</flux:label>

                <flux:textarea wire:model="note" rows="4" />

                <flux:error name="note" />
            </flux:field>

            <div class="flex justify-end">

                <flux:button type="submit" variant="primary">
                    Create Adjustment
                </flux:button>

            </div>

        </flux:card>

    </form>

</div>
