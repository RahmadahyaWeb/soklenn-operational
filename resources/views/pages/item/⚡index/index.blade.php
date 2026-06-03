<div>

    <x-page-header title="Items" description="Manage operational and non operational inventory items."
        button-label="Add Item" :button-href="route('items.create')" />

    <flux:card class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <flux:field>
                <flux:input wire:model.live.debounce.300ms="search" placeholder="Search item..." />
            </flux:field>

            <flux:field>
                <flux:select wire:model.live="category">

                    <flux:select.option value="">
                        All Categories
                    </flux:select.option>

                    @foreach ($this->categories as $category)
                        <flux:select.option :value="$category->id">
                            {{ $category->name }}
                        </flux:select.option>
                    @endforeach

                </flux:select>
            </flux:field>

            <flux:field>
                <flux:select wire:model.live="status">

                    <flux:select.option value="">
                        All Status
                    </flux:select.option>

                    <flux:select.option value="1">
                        Active
                    </flux:select.option>

                    <flux:select.option value="0">
                        Inactive
                    </flux:select.option>

                </flux:select>
            </flux:field>

        </div>

        <flux:table :paginate="$this->items">

            <flux:table.columns>
                <flux:table.column>Item</flux:table.column>
                <flux:table.column>Category</flux:table.column>
                <flux:table.column>Unit</flux:table.column>
                <flux:table.column>Stock</flux:table.column>
                <flux:table.column>Minimum Stock</flux:table.column>
                <flux:table.column>Buy Price</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>

                @forelse ($this->items as $item)
                    <flux:table.row :key="$item->id">

                        <flux:table.cell>
                            {{ $item->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $item->category?->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $item->unit ?: '-' }}
                        </flux:table.cell>

                        <flux:table.cell>

                            @if ($item->stock <= $item->minimum_stock)
                                <flux:badge color="amber">
                                    {{ $item->stock }}
                                </flux:badge>
                            @else
                                {{ $item->stock }}
                            @endif

                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $item->minimum_stock }}
                        </flux:table.cell>

                        <flux:table.cell>
                            Rp {{ number_format($item->buy_price, 0, ',', '.') }}
                        </flux:table.cell>

                        <flux:table.cell>

                            @if ($item->is_active)
                                <flux:badge color="green">
                                    Active
                                </flux:badge>
                            @else
                                <flux:badge color="red">
                                    Inactive
                                </flux:badge>
                            @endif

                        </flux:table.cell>

                        <flux:table.cell align="end">

                            <flux:dropdown>

                                <flux:button variant="ghost" size="sm" icon:trailing="ellipsis-horizontal" />

                                <flux:menu>

                                    <flux:menu.item icon="pencil" href="{{ route('items.edit', $item) }}">
                                        Edit
                                    </flux:menu.item>

                                    <flux:menu.separator />

                                    <flux:menu.item icon="trash" variant="danger"
                                        wire:click="confirmDelete({{ $item->id }})">
                                        Delete
                                    </flux:menu.item>

                                </flux:menu>

                            </flux:dropdown>

                        </flux:table.cell>

                    </flux:table.row>

                @empty

                    <flux:table.row>

                        <flux:table.cell colspan="8" class="text-center">
                            No items found.
                        </flux:table.cell>

                    </flux:table.row>
                @endforelse

            </flux:table.rows>

        </flux:table>

    </flux:card>

    <x-delete-modal name="delete-item" heading="Delete Item?"
        message="You're about to delete this item.<br>This action cannot be reversed." action="destroy" />

</div>
