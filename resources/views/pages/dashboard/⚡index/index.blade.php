<div class="space-y-6">

    <x-page-header title="Dashboard" description="Monitor daily operations, orders, finance, and inventory." />

    {{-- KPI HARI INI --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <flux:card>
            <div class="space-y-1">
                <div class="text-sm text-zinc-500">
                    Orders Today
                </div>

                <div class="text-4xl font-bold">
                    {{ $this->todayOrders }}
                </div>
            </div>
        </flux:card>

        <flux:card>
            <div class="space-y-1">
                <div class="text-sm text-zinc-500">
                    Income Today
                </div>

                <div class="text-3xl font-bold">
                    Rp {{ number_format($this->todayIncome, 0, ',', '.') }}
                </div>
            </div>
        </flux:card>

        <flux:card>
            <div class="space-y-1">
                <div class="text-sm text-zinc-500">
                    Profit Today
                </div>

                <div class="text-3xl font-bold">
                    Rp {{ number_format($this->todayProfit, 0, ',', '.') }}
                </div>
            </div>
        </flux:card>

    </div>

    {{-- WORKFLOW ORDER --}}
    <flux:card class="space-y-5">

        <div>
            <flux:heading size="lg">
                Order Workflow
            </flux:heading>

            <flux:text>
                Current order progress across all statuses.
            </flux:text>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">

            <div class="rounded-xl border p-4 text-center">
                <div class="text-sm text-zinc-500">
                    Pending
                </div>

                <div class="text-3xl font-bold mt-2">
                    {{ $this->pendingOrders }}
                </div>
            </div>

            <div class="rounded-xl border p-4 text-center">
                <div class="text-sm text-zinc-500">
                    Washing
                </div>

                <div class="text-3xl font-bold mt-2">
                    {{ $this->washingOrders }}
                </div>
            </div>

            <div class="rounded-xl border p-4 text-center">
                <div class="text-sm text-zinc-500">
                    Drying
                </div>

                <div class="text-3xl font-bold mt-2">
                    {{ $this->dryingOrders }}
                </div>
            </div>

            <div class="rounded-xl border p-4 text-center">
                <div class="text-sm text-zinc-500">
                    Finished
                </div>

                <div class="text-3xl font-bold mt-2">
                    {{ $this->finishedOrders }}
                </div>
            </div>

            <div class="rounded-xl border p-4 text-center">
                <div class="text-sm text-zinc-500">
                    Picked Up
                </div>

                <div class="text-3xl font-bold mt-2">
                    {{ $this->pickedUpOrders }}
                </div>
            </div>

        </div>

    </flux:card>

    {{-- PERLU PERHATIAN + ORDER TERBARU --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <flux:card class="space-y-4">

            <div>
                <flux:heading size="lg">
                    Need Attention
                </flux:heading>

                <flux:text>
                    Low stock inventory items.
                </flux:text>
            </div>

            @forelse ($this->lowStockItems as $item)
                <div class="flex items-center justify-between">

                    <div>
                        <div class="font-medium">
                            {{ $item->name }}
                        </div>

                        <div class="text-sm text-zinc-500">
                            Minimum {{ $item->minimum_stock }}
                        </div>
                    </div>

                    <flux:badge color="red">
                        {{ $item->stock }} {{ $item->unit }}
                    </flux:badge>

                </div>

            @empty

                <div class="text-sm text-zinc-500">
                    No low stock items.
                </div>
            @endforelse

        </flux:card>

        <flux:card class="xl:col-span-2 space-y-4">

            <div>
                <flux:heading size="lg">
                    Recent Orders
                </flux:heading>

                <flux:text>
                    Latest customer orders.
                </flux:text>
            </div>

            @forelse ($this->recentOrders as $order)
                <div class="flex items-center justify-between">

                    <div>
                        <div class="font-medium">
                            {{ $order->invoice_number }}
                        </div>

                        <div class="text-sm text-zinc-500">
                            {{ $order->customer?->name ?: 'Walk In Customer' }}
                        </div>
                    </div>

                    <div class="flex items-center gap-3">

                        <div class="font-semibold">
                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                        </div>

                        <flux:badge :color="$this->statusBadgeColor($order->status)">
                            {{ str($order->status)->replace('_', ' ')->title() }}
                        </flux:badge>

                    </div>

                </div>

            @empty

                <div class="text-sm text-zinc-500">
                    No recent orders.
                </div>
            @endforelse

        </flux:card>

    </div>

    {{-- FINANCE + INVENTORY --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        <flux:card class="space-y-5">

            <div>
                <flux:heading size="lg">
                    Finance Summary
                </flux:heading>

                <flux:text>
                    Current month performance.
                </flux:text>
            </div>

            <div class="grid grid-cols-3 gap-4">

                <div>
                    <div class="text-sm text-zinc-500">
                        Income
                    </div>

                    <div class="font-bold text-lg">
                        Rp {{ number_format($this->monthlyIncome, 0, ',', '.') }}
                    </div>
                </div>

                <div>
                    <div class="text-sm text-zinc-500">
                        Expense
                    </div>

                    <div class="font-bold text-lg">
                        Rp {{ number_format($this->monthlyExpense, 0, ',', '.') }}
                    </div>
                </div>

                <div>
                    <div class="text-sm text-zinc-500">
                        Profit
                    </div>

                    <div class="font-bold text-lg">
                        Rp {{ number_format($this->monthlyProfit, 0, ',', '.') }}
                    </div>
                </div>

            </div>

            <div class="border-t pt-4 space-y-3">

                @forelse ($this->recentIncomes as $income)
                    <div class="flex justify-between">

                        <span>
                            {{ $income->title }}
                        </span>

                        <span class="font-medium">
                            Rp {{ number_format($income->amount, 0, ',', '.') }}
                        </span>

                    </div>

                @empty

                    <div class="text-sm text-zinc-500">
                        No income records.
                    </div>
                @endforelse

            </div>

        </flux:card>

        <flux:card class="space-y-5">

            <div>
                <flux:heading size="lg">
                    Inventory Activity
                </flux:heading>

                <flux:text>
                    Latest purchase and stock movement.
                </flux:text>
            </div>

            @forelse ($this->recentPurchases as $purchase)
                <div class="flex justify-between">

                    <div>
                        <div class="font-medium">
                            {{ $purchase->item?->name }}
                        </div>

                        <div class="text-sm text-zinc-500">
                            {{ $purchase->supplier?->name }}
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="font-medium">
                            {{ $purchase->qty }} pcs
                        </div>
                    </div>

                </div>

            @empty

                <div class="text-sm text-zinc-500">
                    No purchase records.
                </div>
            @endforelse

            <div class="border-t pt-4 space-y-3">

                @forelse ($this->recentStockAdjustments as $adjustment)
                    <div class="flex justify-between">

                        <span>
                            {{ $adjustment->item?->name }}
                        </span>

                        <flux:badge :color="$adjustment->type === 'in' ? 'green' : 'red'">
                            {{ strtoupper($adjustment->type) }}
                            {{ $adjustment->qty }}
                        </flux:badge>

                    </div>

                @empty

                    <div class="text-sm text-zinc-500">
                        No stock adjustments.
                    </div>
                @endforelse

            </div>

        </flux:card>

    </div>

</div>
