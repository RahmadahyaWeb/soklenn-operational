<div class="space-y-6">

    <x-page-header title="Dashboard"
        description="Monitor business operations, finance, inventory, and order activity in real time." />

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

        <flux:card>
            <div class="space-y-2">
                <div class="text-sm text-zinc-500">
                    Today Orders
                </div>

                <div class="text-3xl font-bold">
                    {{ $this->todayOrders }}
                </div>
            </div>
        </flux:card>

        <flux:card>
            <div class="space-y-2">
                <div class="text-sm text-zinc-500">
                    Today Income
                </div>

                <div class="text-3xl font-bold">
                    Rp {{ number_format($this->todayIncome, 0, ',', '.') }}
                </div>
            </div>
        </flux:card>

        <flux:card>
            <div class="space-y-2">
                <div class="text-sm text-zinc-500">
                    Today Expense
                </div>

                <div class="text-3xl font-bold">
                    Rp {{ number_format($this->todayExpense, 0, ',', '.') }}
                </div>
            </div>
        </flux:card>

        <flux:card>
            <div class="space-y-2">
                <div class="text-sm text-zinc-500">
                    Today Profit
                </div>

                <div class="text-3xl font-bold">
                    Rp {{ number_format($this->todayProfit, 0, ',', '.') }}
                </div>
            </div>
        </flux:card>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <flux:card>
            <div class="space-y-2">
                <div class="text-sm text-zinc-500">
                    Monthly Income
                </div>

                <div class="text-2xl font-bold">
                    Rp {{ number_format($this->monthlyIncome, 0, ',', '.') }}
                </div>
            </div>
        </flux:card>

        <flux:card>
            <div class="space-y-2">
                <div class="text-sm text-zinc-500">
                    Monthly Expense
                </div>

                <div class="text-2xl font-bold">
                    Rp {{ number_format($this->monthlyExpense, 0, ',', '.') }}
                </div>
            </div>
        </flux:card>

        <flux:card>
            <div class="space-y-2">
                <div class="text-sm text-zinc-500">
                    Monthly Profit
                </div>

                <div class="text-2xl font-bold">
                    Rp {{ number_format($this->monthlyProfit, 0, ',', '.') }}
                </div>
            </div>
        </flux:card>

    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        <flux:card class="space-y-4">

            <div class="flex items-center justify-between">
                <div class="font-semibold">
                    Order Status
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">

                <div class="space-y-1">
                    <div class="text-sm text-zinc-500">Pending</div>
                    <div class="text-2xl font-bold">
                        {{ $this->pendingOrders }}
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="text-sm text-zinc-500">Washing</div>
                    <div class="text-2xl font-bold">
                        {{ $this->washingOrders }}
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="text-sm text-zinc-500">Drying</div>
                    <div class="text-2xl font-bold">
                        {{ $this->dryingOrders }}
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="text-sm text-zinc-500">Finished</div>
                    <div class="text-2xl font-bold">
                        {{ $this->finishedOrders }}
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="text-sm text-zinc-500">Picked Up</div>
                    <div class="text-2xl font-bold">
                        {{ $this->pickedUpOrders }}
                    </div>
                </div>

            </div>

        </flux:card>

        <flux:card class="space-y-4">

            <div class="font-semibold">
                Low Stock Items
            </div>

            <div class="space-y-3">

                @forelse ($this->lowStockItems as $item)
                    <div class="flex items-center justify-between">

                        <div>
                            <div class="font-medium">
                                {{ $item->name }}
                            </div>

                            <div class="text-sm text-zinc-500">
                                Minimum: {{ $item->minimum_stock }}
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

            </div>

        </flux:card>

    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        <flux:card class="space-y-4">

            <div class="font-semibold">
                Recent Orders
            </div>

            <div class="space-y-3">

                @forelse ($this->recentOrders as $order)
                    <div class="flex items-center justify-between">

                        <div>
                            <div class="font-medium">
                                {{ $order->invoice_number }}
                            </div>

                            <div class="text-sm text-zinc-500">
                                {{ $order->customer?->name }}
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

            </div>

        </flux:card>

        <flux:card class="space-y-4">

            <div class="font-semibold">
                Recent Purchases
            </div>

            <div class="space-y-3">

                @forelse ($this->recentPurchases as $purchase)
                    <div class="flex items-center justify-between">

                        <div>
                            <div class="font-medium">
                                {{ $purchase->item?->name }}
                            </div>

                            <div class="text-sm text-zinc-500">
                                {{ $purchase->supplier?->name }}
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="font-semibold">
                                {{ $purchase->qty }} pcs
                            </div>

                            <div class="text-sm text-zinc-500">
                                Rp {{ number_format($purchase->total, 0, ',', '.') }}
                            </div>
                        </div>

                    </div>

                @empty

                    <div class="text-sm text-zinc-500">
                        No recent purchases.
                    </div>
                @endforelse

            </div>

        </flux:card>

    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <flux:card class="space-y-4">

            <div class="font-semibold">
                Recent Incomes
            </div>

            <div class="space-y-3">

                @forelse ($this->recentIncomes as $income)
                    <div class="flex items-center justify-between">

                        <div>
                            <div class="font-medium">
                                {{ $income->title }}
                            </div>

                            <div class="text-sm text-zinc-500">
                                {{ $income->transaction_date?->format('d M Y') }}
                            </div>
                        </div>

                        <div class="font-semibold">
                            Rp {{ number_format($income->amount, 0, ',', '.') }}
                        </div>

                    </div>

                @empty

                    <div class="text-sm text-zinc-500">
                        No recent incomes.
                    </div>
                @endforelse

            </div>

        </flux:card>

        <flux:card class="space-y-4">

            <div class="font-semibold">
                Recent Expenses
            </div>

            <div class="space-y-3">

                @forelse ($this->recentExpenses as $expense)
                    <div class="flex items-center justify-between">

                        <div>
                            <div class="font-medium">
                                {{ $expense->title }}
                            </div>

                            <div class="text-sm text-zinc-500">
                                {{ $expense->transaction_date?->format('d M Y') }}
                            </div>
                        </div>

                        <div class="font-semibold">
                            Rp {{ number_format($expense->amount, 0, ',', '.') }}
                        </div>

                    </div>

                @empty

                    <div class="text-sm text-zinc-500">
                        No recent expenses.
                    </div>
                @endforelse

            </div>

        </flux:card>

        <flux:card class="space-y-4">

            <div class="font-semibold">
                Recent Stock Adjustments
            </div>

            <div class="space-y-3">

                @forelse ($this->recentStockAdjustments as $adjustment)
                    <div class="flex items-center justify-between">

                        <div>
                            <div class="font-medium">
                                {{ $adjustment->item?->name }}
                            </div>

                            <div class="text-sm text-zinc-500">
                                {{ $adjustment->adjustment_date?->format('d M Y') }}
                            </div>
                        </div>

                        <flux:badge :color="$adjustment->type === 'in' ? 'green' : 'red'">
                            {{ strtoupper($adjustment->type) }}
                            {{ $adjustment->qty }}
                        </flux:badge>

                    </div>

                @empty

                    <div class="text-sm text-zinc-500">
                        No recent stock adjustments.
                    </div>
                @endforelse

            </div>

        </flux:card>

    </div>

</div>
