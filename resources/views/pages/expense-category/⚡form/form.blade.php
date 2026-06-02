<div class="space-y-6">

    <x-page-header :title="$expenseCategory ? 'Edit Expense Category' : 'Create Expense Category'" :description="$expenseCategory
        ? 'Update expense category information.'
        : 'Create a new category for business expenses.'" />

    <form wire:submit.prevent="save">

        <flux:card class="space-y-6">

            <flux:field>
                <flux:label>Name</flux:label>

                <flux:input wire:model="name" type="text" />

                <flux:error name="name" />
            </flux:field>

            <div class="flex justify-end">

                <flux:button type="submit" variant="primary">
                    {{ $expenseCategory ? 'Update' : 'Create' }}
                </flux:button>

            </div>

        </flux:card>

    </form>

</div>
