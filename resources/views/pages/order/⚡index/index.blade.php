<div class="space-y-6">

    <x-page-header title="Orders" description="Manage customer shoe cleaning orders and workflow progress."
        button-label="Add Order" :button-href="route('orders.create')" />

    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-6">

        @foreach ($this->statistics() as $status => $stat)
            <button wire:click="$set('status', '{{ $status }}')" @class([
                'rounded-xl border p-4 text-left transition',
                'border-zinc-900 bg-zinc-50 dark:border-white dark:bg-zinc-900' =>
                    $status === $this->status,
                'border-zinc-200 hover:border-zinc-400 dark:border-zinc-700 dark:hover:border-zinc-500' =>
                    $status !== $this->status,
            ])>

                <div class="flex items-center justify-between">

                    <span class="text-sm text-zinc-500">
                        {{ $this->statisticLabels()[$status] }}
                    </span>

                    <flux:badge :color="$this->statisticColor($status)">
                        {{ $stat['count'] }}
                    </flux:badge>

                </div>

                <div class="mt-3">

                    <div class="text-3xl font-bold">
                        {{ $stat['count'] }}
                    </div>

                    <div class="mt-1 text-sm text-zinc-500">
                        Rp {{ number_format($stat['total'], 0, ',', '.') }}
                    </div>

                </div>

            </button>
        @endforeach

    </div>

    <flux:card class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <flux:field>
                <flux:label>Search</flux:label>

                <flux:input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Invoice or customer..." />
            </flux:field>

            <flux:field>
                <flux:label>Status</flux:label>

                <flux:select wire:model.live="status">

                    <flux:select.option value="">
                        All Status
                    </flux:select.option>

                    @foreach ($this->statuses() as $value => $label)
                        <flux:select.option :value="$value">
                            {{ $label }}
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

                                @if ($this->nextStatus($order->status))
                                    <flux:dropdown>

                                        <flux:button size="sm" variant="ghost" icon="arrow-path" />

                                        <flux:menu>

                                            <flux:menu.item icon="arrow-right"
                                                wire:click="updateStatus(
                            {{ $order->id }},
                            '{{ $this->nextStatus($order->status) }}'
                        )">
                                                {{ $this->nextStatusLabel($order->status) }}
                                            </flux:menu.item>

                                        </flux:menu>

                                    </flux:dropdown>
                                @endif

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

                                    <flux:menu.separator />

                                    <flux:menu.item icon="eye"
                                        href="{{ route('invoice.public', $order->public_token) }}" target="_blank">

                                        Lihat Invoice

                                    </flux:menu.item>

                                    <flux:menu.item icon="link"
                                        x-on:click="
        navigator.clipboard.writeText('{{ route('invoice.public', $order->public_token) }}');
        $flux.toast({
            heading: 'Berhasil',
            text: 'Link invoice berhasil disalin.'
        });
    ">

                                        Salin Link Invoice

                                    </flux:menu.item>

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
