<div class="space-y-6">

    <x-page-header :title="$itemPurchase ? 'Edit Item Purchase' : 'Create Item Purchase'" :description="$itemPurchase
        ? 'Update inventory purchase transaction.'
        : 'Create a new inventory purchase and automatically update stock.'" />

    <form wire:submit.prevent="save">

        <flux:card class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <flux:field>
                    <flux:label>Supplier</flux:label>

                    <flux:select wire:model="supplier_id">

                        <flux:select.option value="">
                            Select Supplier
                        </flux:select.option>

                        @foreach ($this->suppliers as $supplier)
                            <flux:select.option :value="$supplier->id">
                                {{ $supplier->name }}
                            </flux:select.option>
                        @endforeach

                    </flux:select>

                    <flux:error name="supplier_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Purchase Date</flux:label>

                    <flux:input wire:model="purchase_date" type="date" />

                    <flux:error name="purchase_date" />
                </flux:field>

                <flux:field>
                    <flux:label>Item</flux:label>

                    <flux:select wire:model="item_id">

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
                    <flux:label>Qty</flux:label>

                    <flux:input wire:model.live="qty" type="number" min="1" />

                    <flux:error name="qty" />
                </flux:field>

                <flux:field>
                    <flux:label>Price</flux:label>

                    <flux:input wire:model.live="price" type="number" min="0" />

                    <flux:error name="price" />
                </flux:field>

                <flux:field>
                    <flux:label>Total</flux:label>

                    <flux:input wire:model="total" type="number" readonly />
                </flux:field>

            </div>

            <flux:field>
                <flux:label>Note</flux:label>

                <flux:textarea wire:model="note" rows="4" />

                <flux:error name="note" />
            </flux:field>

            <div class="flex justify-end">

                <flux:button type="submit" variant="primary">
                    {{ $itemPurchase ? 'Update' : 'Create' }}
                </flux:button>

            </div>

        </flux:card>

    </form>

</div>
