<div>
    <x-page-header title="Categories"
        description="Manage item categories for operational and non operational business needs."
        button-label="Add Category" :button-href="route('categories.create')" />

    <flux:card>
        <flux:table :paginate="$this->categories">

            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Type</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->categories as $category)
                    <flux:table.row :key="$category->id">

                        <flux:table.cell>
                            {{ $category->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ str($category->type)->replace('_', ' ')->title() }}
                        </flux:table.cell>

                        <flux:table.cell align="end">
                            <flux:dropdown>

                                <flux:button variant="ghost" size="sm" icon:trailing="ellipsis-horizontal" />

                                <flux:menu>

                                    <flux:menu.item icon="pencil" href="{{ route('categories.edit', $category) }}">
                                        Edit
                                    </flux:menu.item>

                                    <flux:menu.separator />

                                    <flux:menu.item icon="trash" variant="danger"
                                        wire:click="confirmDelete({{ $category->id }})">
                                        Delete
                                    </flux:menu.item>

                                </flux:menu>

                            </flux:dropdown>
                        </flux:table.cell>

                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="3" class="text-center">
                            No categories found.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>

        </flux:table>
    </flux:card>

    <x-delete-modal name="delete-category" heading="Delete Category?"
        message="You're about to delete this category.<br>This action cannot be reversed." action="destroy" />
</div>
