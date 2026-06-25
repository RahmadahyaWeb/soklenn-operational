<div>

    <x-page-header title="Orders" description="Manage customer shoe cleaning orders and workflow progress."
        button-label="Add Order" :button-href="route('orders.create')" />

    <flux:card class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <flux:field>
                <flux:label>Search</flux:label>

                <flux:input wire:model.live.debounce.300ms="search" type="text" placeholder="Invoice or customer..." />
            </flux:field>

            <flux:field>
                <flux:label>Status</flux:label>

                <flux:select wire:model.live="status">

                    <flux:select.option value="">
                        All Status
                    </flux:select.option>

                    @foreach ($this->statuses() as $status)
                        <flux:select.option :value="$status">
                            {{ str($status)->replace('_', ' ')->title() }}
                        </flux:select.option>
                    @endforeach

                </flux:select>
            </flux:field>

        </div>

        <flux:table :paginate="$this->orders">

            <flux:table.columns>
                <flux:table.column>Invoice</flux:table.column>
                <flux:table.column>Customer</flux:table.column>
                <flux:table.column>Services</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Received</flux:table.column>
                <flux:table.column>Total</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>

                @forelse ($this->orders as $order)

                    <flux:table.row :key="$order->id">

                        <flux:table.cell>
                            {{ $order->invoice_number }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $order->customer?->name ?? '-' }}
                        </flux:table.cell>

                        <flux:table.cell>

                            <div class="space-y-1">

                                @foreach ($order->details as $detail)
                                    <div>
                                        {{ $detail->service?->name }}
                                        ({{ $detail->qty }}x)
                                    </div>
                                @endforeach

                            </div>

                        </flux:table.cell>

                        <flux:table.cell>

                            <div class="flex items-center gap-2">

                                <flux:badge :color="$this->statusBadgeColor($order->status)">
                                    {{ str($order->status)->replace('_', ' ')->title() }}
                                </flux:badge>

                                {{-- <flux:dropdown>

                                    <flux:button size="sm" variant="subtle" icon="arrow-path">
                                        Update
                                    </flux:button>

                                    <flux:menu>

                                        @foreach ($this->statuses() as $status)
                                            <flux:menu.item
                                                wire:click="updateStatus({{ $order->id }}, '{{ $status }}')">
                                                {{ str($status)->replace('_', ' ')->title() }}
                                            </flux:menu.item>
                                        @endforeach

                                    </flux:menu>

                                </flux:dropdown> --}}

                            </div>

                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $order->received_at?->format('d M Y H:i') }}
                        </flux:table.cell>

                        <flux:table.cell>
                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                        </flux:table.cell>

                        <flux:table.cell align="end">

                            <flux:dropdown>

                                <flux:button variant="ghost" size="sm" icon:trailing="ellipsis-horizontal" />

                                <flux:menu>

                                    <flux:menu.item icon="pencil" href="{{ route('orders.edit', $order) }}">
                                        Edit
                                    </flux:menu.item>

                                    @if ($order->status === 'pending')
                                        <flux:menu.separator />

                                        <flux:menu.item icon="trash" variant="danger"
                                            wire:click="confirmDelete({{ $order->id }})">
                                            Delete
                                        </flux:menu.item>
                                    @endif

                                </flux:menu>

                            </flux:dropdown>

                        </flux:table.cell>

                    </flux:table.row>

                @empty

                    <flux:table.row>

                        <flux:table.cell colspan="7" class="text-center">
                            No orders found.
                        </flux:table.cell>

                    </flux:table.row>
                @endforelse

            </flux:table.rows>

        </flux:table>

    </flux:card>

    <x-delete-modal name="delete-order" heading="Delete Order?"
        message="You're about to delete this order.<br>This action cannot be reversed." action="destroy" />

</div>
