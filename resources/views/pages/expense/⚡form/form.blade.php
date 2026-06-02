<div class="space-y-6">

    <x-page-header :title="$expense ? 'Edit Expense' : 'Create Expense'" :description="$expense ? 'Update expense transaction information.' : 'Create a new manual expense transaction.'" />

    <form wire:submit.prevent="save">

        <flux:card class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <flux:field>
                    <flux:label>Category</flux:label>

                    <flux:select wire:model="expense_category_id">

                        <flux:select.option value="">
                            Select Category
                        </flux:select.option>

                        @foreach ($this->expenseCategories as $category)
                            <flux:select.option :value="$category->id">
                                {{ $category->name }}
                            </flux:select.option>
                        @endforeach

                    </flux:select>

                    <flux:error name="expense_category_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Transaction Date</flux:label>

                    <flux:input wire:model="transaction_date" type="date" />

                    <flux:error name="transaction_date" />
                </flux:field>

                <flux:field class="md:col-span-2">
                    <flux:label>Amount</flux:label>

                    <flux:input wire:model="amount" type="number" min="0" />

                    <flux:error name="amount" />
                </flux:field>

            </div>

            <flux:field>
                <flux:label>Title</flux:label>

                <flux:input wire:model="title" type="text" />

                <flux:error name="title" />
            </flux:field>

            <flux:field>
                <flux:label>Description</flux:label>

                <flux:textarea wire:model="description" rows="4" />

                <flux:error name="description" />
            </flux:field>

            <div class="flex justify-end">

                <flux:button type="submit" variant="primary">
                    {{ $expense ? 'Update' : 'Create' }}
                </flux:button>

            </div>

        </flux:card>

    </form>

</div>
