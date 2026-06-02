<div>

    <x-page-header title="Item Purchases"
        description="Manage inventory purchases, stock movement, and supplier transactions." button-label="Add Purchase"
        :button-href="route('item-purchases.create')" />

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

        <flux:card>

            <div class="space-y-1">

                <div class="text-sm text-zinc-500">
                    Total Purchases
                </div>

                <div class="text-2xl font-bold">
                    Rp {{ number_format($this->totalPurchases, 0, ',', '.') }}
                </div>

            </div>

        </flux:card>

    </div>

    <flux:card class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <flux:field>
                <flux:label>Supplier</flux:label>

                <flux:select wire:model.live="supplier_id">

                    <flux:select.option value="">
                        All Suppliers
                    </flux:select.option>

                    @foreach ($this->suppliers as $supplier)
                        <flux:select.option :value="$supplier->id">
                            {{ $supplier->name }}
                        </flux:select.option>
                    @endforeach

                </flux:select>
            </flux:field>

            <flux:field>
                <flux:label>Item</flux:label>

                <flux:select wire:model.live="item_id">

                    <flux:select.option value="">
                        All Items
                    </flux:select.option>

                    @foreach ($this->items as $item)
                        <flux:select.option :value="$item->id">
                            {{ $item->name }}
                        </flux:select.option>
                    @endforeach

                </flux:select>
            </flux:field>

            <flux:field>
                <flux:label>Start Date</flux:label>

                <flux:input wire:model.live="start_date" type="date" />
            </flux:field>

            <flux:field>
                <flux:label>End Date</flux:label>

                <flux:input wire:model.live="end_date" type="date" />
            </flux:field>

        </div>

        <flux:table :paginate="$this->itemPurchases">

            <flux:table.columns>
                <flux:table.column>Date</flux:table.column>
                <flux:table.column>Supplier</flux:table.column>
                <flux:table.column>Item</flux:table.column>
                <flux:table.column>Qty</flux:table.column>
                <flux:table.column>Price</flux:table.column>
                <flux:table.column>Total</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>

                @forelse ($this->itemPurchases as $itemPurchase)
                    <flux:table.row :key="$itemPurchase->id">

                        <flux:table.cell>
                            {{ $itemPurchase->purchase_date?->format('d M Y') }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $itemPurchase->supplier?->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $itemPurchase->item?->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $itemPurchase->qty }}
                        </flux:table.cell>

                        <flux:table.cell>
                            Rp {{ number_format($itemPurchase->price, 0, ',', '.') }}
                        </flux:table.cell>

                        <flux:table.cell>
                            Rp {{ number_format($itemPurchase->total, 0, ',', '.') }}
                        </flux:table.cell>

                        <flux:table.cell align="end">

                            <flux:dropdown>

                                <flux:button variant="ghost" size="sm" icon:trailing="ellipsis-horizontal" />

                                <flux:menu>

                                    <flux:menu.item icon="pencil"
                                        href="{{ route('item-purchases.edit', $itemPurchase) }}">
                                        Edit
                                    </flux:menu.item>

                                    <flux:menu.separator />

                                    <flux:menu.item icon="trash" variant="danger"
                                        wire:click="confirmDelete({{ $itemPurchase->id }})">
                                        Delete
                                    </flux:menu.item>

                                </flux:menu>

                            </flux:dropdown>

                        </flux:table.cell>

                    </flux:table.row>

                @empty

                    <flux:table.row>

                        <flux:table.cell colspan="7" class="text-center">
                            No item purchases found.
                        </flux:table.cell>

                    </flux:table.row>
                @endforelse

            </flux:table.rows>

        </flux:table>

    </flux:card>

    <x-delete-modal name="delete-item-purchase" heading="Delete Item Purchase?"
        message="You're about to delete this purchase transaction.<br>This action cannot be reversed."
        action="destroy" />

</div>
