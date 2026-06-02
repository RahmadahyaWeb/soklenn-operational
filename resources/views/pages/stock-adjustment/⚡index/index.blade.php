<div>

    <x-page-header title="Stock Adjustments"
        description="Manage manual stock adjustments for inventory correction and monitoring."
        button-label="Add Adjustment" :button-href="route('stock-adjustments.create')" />

    <flux:card class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

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
                <flux:label>Type</flux:label>

                <flux:select wire:model.live="type">

                    <flux:select.option value="">
                        All Types
                    </flux:select.option>

                    <flux:select.option value="in">
                        IN
                    </flux:select.option>

                    <flux:select.option value="out">
                        OUT
                    </flux:select.option>

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

        <flux:table :paginate="$this->stockAdjustments">

            <flux:table.columns>
                <flux:table.column>Date</flux:table.column>
                <flux:table.column>Item</flux:table.column>
                <flux:table.column>Type</flux:table.column>
                <flux:table.column>Qty</flux:table.column>
                <flux:table.column>Before</flux:table.column>
                <flux:table.column>After</flux:table.column>
                <flux:table.column>Note</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>

                @forelse ($this->stockAdjustments as $adjustment)
                    <flux:table.row :key="$adjustment->id">

                        <flux:table.cell>
                            {{ $adjustment->adjustment_date?->format('d M Y') }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $adjustment->item?->name }}
                        </flux:table.cell>

                        <flux:table.cell>

                            @if ($adjustment->type === 'in')
                                <flux:badge color="green">
                                    IN
                                </flux:badge>
                            @else
                                <flux:badge color="red">
                                    OUT
                                </flux:badge>
                            @endif

                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $adjustment->qty }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $adjustment->before_stock }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $adjustment->after_stock }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $adjustment->note ?? '-' }}
                        </flux:table.cell>

                    </flux:table.row>

                @empty

                    <flux:table.row>

                        <flux:table.cell colspan="7" class="text-center">
                            No stock adjustments found.
                        </flux:table.cell>

                    </flux:table.row>
                @endforelse

            </flux:table.rows>

        </flux:table>

    </flux:card>

</div>
