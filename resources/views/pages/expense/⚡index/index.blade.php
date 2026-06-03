<div>

    <x-page-header title="Expenses" description="Manage and monitor business expense transactions."
        button-label="Add Expense" :button-href="route('expenses.create')" />

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

        <flux:card>

            <div class="space-y-1">

                <div class="text-sm text-zinc-500">
                    Total Expense
                </div>

                <div class="text-2xl font-bold">
                    Rp {{ number_format($this->totalExpense, 0, ',', '.') }}
                </div>

            </div>

        </flux:card>

    </div>

    <flux:card class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <flux:field>
                <flux:label>Search</flux:label>

                <flux:input wire:model.live.debounce.300ms="search" type="text" placeholder="Search title..." />
            </flux:field>

            <flux:field>
                <flux:label>Category</flux:label>

                <flux:select wire:model.live="expense_category_id">

                    <flux:select.option value="">
                        All Categories
                    </flux:select.option>

                    @foreach ($this->expenseCategories as $category)
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

        <flux:table :paginate="$this->expenses">

            <flux:table.columns>
                <flux:table.column>Date</flux:table.column>
                <flux:table.column>Title</flux:table.column>
                <flux:table.column>Category</flux:table.column>
                <flux:table.column>Source</flux:table.column>
                <flux:table.column>Amount</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>

                @forelse ($this->expenses as $expense)
                    <flux:table.row :key="$expense->id">

                        <flux:table.cell>
                            {{ $expense->transaction_date?->format('d M Y') }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $expense->title }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $expense->category?->name }}
                        </flux:table.cell>

                        <flux:table.cell>

                            @if ($expense->item_purchase_id)
                                <flux:badge color="blue">
                                    Purchase
                                </flux:badge>
                            @else
                                <flux:badge color="zinc">
                                    Manual
                                </flux:badge>
                            @endif

                        </flux:table.cell>

                        <flux:table.cell>
                            Rp {{ number_format($expense->amount, 0, ',', '.') }}
                        </flux:table.cell>

                        <flux:table.cell align="end">
                            @if (!$expense->item_purchase_id)
                                <flux:dropdown>

                                    <flux:button variant="ghost" size="sm" icon:trailing="ellipsis-horizontal" />

                                    <flux:menu>

                                        <flux:menu.item icon="pencil" href="{{ route('expenses.edit', $expense) }}">
                                            Edit
                                        </flux:menu.item>

                                        <flux:menu.separator />

                                        <flux:menu.item icon="trash" variant="danger"
                                            wire:click="confirmDelete({{ $expense->id }})">
                                            Delete
                                        </flux:menu.item>

                                    </flux:menu>

                                </flux:dropdown>
                            @endif

                        </flux:table.cell>

                    </flux:table.row>

                @empty

                    <flux:table.row>

                        <flux:table.cell colspan="6" class="text-center">
                            No expenses found.
                        </flux:table.cell>

                    </flux:table.row>
                @endforelse

            </flux:table.rows>

        </flux:table>

    </flux:card>

    <x-delete-modal name="delete-expense" heading="Delete Expense?"
        message="You're about to delete this expense transaction.<br>This action cannot be reversed."
        action="destroy" />

</div>
