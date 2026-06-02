<div>

    <x-page-header title="Expense Categories"
        description="Manage categories for operational and non operational business expenses."
        button-label="Add Expense Category" :button-href="route('expense-categories.create')" />

    <flux:card>

        <flux:table :paginate="$this->expenseCategories">

            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>

                @forelse ($this->expenseCategories as $expenseCategory)
                    <flux:table.row :key="$expenseCategory->id">

                        <flux:table.cell>
                            {{ $expenseCategory->name }}
                        </flux:table.cell>

                        <flux:table.cell align="end">

                            <flux:dropdown>

                                <flux:button variant="ghost" size="sm" icon:trailing="ellipsis-horizontal" />

                                <flux:menu>

                                    <flux:menu.item icon="pencil"
                                        href="{{ route('expense-categories.edit', $expenseCategory) }}">
                                        Edit
                                    </flux:menu.item>

                                    <flux:menu.separator />

                                    <flux:menu.item icon="trash" variant="danger"
                                        wire:click="confirmDelete({{ $expenseCategory->id }})">
                                        Delete
                                    </flux:menu.item>

                                </flux:menu>

                            </flux:dropdown>

                        </flux:table.cell>

                    </flux:table.row>

                @empty

                    <flux:table.row>

                        <flux:table.cell colspan="2" class="text-center">
                            No expense categories found.
                        </flux:table.cell>

                    </flux:table.row>
                @endforelse

            </flux:table.rows>

        </flux:table>

    </flux:card>

    <x-delete-modal name="delete-expense-category" heading="Delete Expense Category?"
        message="You're about to delete this expense category.<br>This action cannot be reversed." action="destroy" />

</div>
