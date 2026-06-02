<div>

    <x-page-header title="Incomes" description="Manage and monitor business income transactions." button-label="Add Income"
        :button-href="route('incomes.create')" />

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

        <flux:card>

            <div class="space-y-1">

                <div class="text-sm text-zinc-500">
                    Total Income
                </div>

                <div class="text-2xl font-bold">
                    Rp {{ number_format($this->totalIncome, 0, ',', '.') }}
                </div>

            </div>

        </flux:card>

    </div>

    <flux:card class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <flux:field>
                <flux:label>Search</flux:label>

                <flux:input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Title, invoice, customer..." />
            </flux:field>

            <flux:field>
                <flux:label>Category</flux:label>

                <flux:select wire:model.live="income_category_id">

                    <flux:select.option value="">
                        All Categories
                    </flux:select.option>

                    @foreach ($this->incomeCategories as $category)
                        <flux:select.option :value="$category->id">
                            {{ $category->name }}
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

        <flux:table :paginate="$this->incomes">

            <flux:table.columns>
                <flux:table.column>Date</flux:table.column>
                <flux:table.column>Title</flux:table.column>
                <flux:table.column>Category</flux:table.column>
                <flux:table.column>Invoice</flux:table.column>
                <flux:table.column>Customer</flux:table.column>
                <flux:table.column>Amount</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>

                @forelse ($this->incomes as $income)
                    <flux:table.row :key="$income->id">

                        <flux:table.cell>
                            {{ $income->transaction_date?->format('d M Y') }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $income->title }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $income->category?->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $income->order?->invoice_number ?? '-' }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $income->order?->customer?->name ?? '-' }}
                        </flux:table.cell>

                        <flux:table.cell>
                            Rp {{ number_format($income->amount, 0, ',', '.') }}
                        </flux:table.cell>

                        <flux:table.cell align="end">

                            @if (!$income->order_id)
                                <flux:dropdown>

                                    <flux:button variant="ghost" size="sm" icon:trailing="ellipsis-horizontal" />

                                    <flux:menu>

                                        <flux:menu.item icon="pencil" href="{{ route('incomes.edit', $income) }}">
                                            Edit
                                        </flux:menu.item>

                                        <flux:menu.separator />

                                        <flux:menu.item icon="trash" variant="danger"
                                            wire:click="confirmDelete({{ $income->id }})">
                                            Delete
                                        </flux:menu.item>

                                    </flux:menu>

                                </flux:dropdown>
                            @endif

                        </flux:table.cell>

                    </flux:table.row>

                @empty

                    <flux:table.row>

                        <flux:table.cell colspan="7" class="text-center">
                            No incomes found.
                        </flux:table.cell>

                    </flux:table.row>
                @endforelse

            </flux:table.rows>

        </flux:table>

    </flux:card>

    <x-delete-modal name="delete-income" heading="Delete Income?"
        message="You're about to delete this income transaction.<br>This action cannot be reversed." action="destroy" />

</div>
