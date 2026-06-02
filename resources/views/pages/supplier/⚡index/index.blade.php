<div>

    <x-page-header title="Suppliers"
        description="Manage supplier information for inventory purchasing and operational needs."
        button-label="Add Supplier" :button-href="route('suppliers.create')" />

    <flux:card>

        <flux:table :paginate="$this->suppliers">

            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Phone</flux:table.column>
                <flux:table.column>Address</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>

                @forelse ($this->suppliers as $supplier)
                    <flux:table.row :key="$supplier->id">

                        <flux:table.cell>
                            {{ $supplier->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $supplier->phone ?? '-' }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $supplier->address ?? '-' }}
                        </flux:table.cell>

                        <flux:table.cell align="end">

                            <flux:dropdown>

                                <flux:button variant="ghost" size="sm" icon:trailing="ellipsis-horizontal" />

                                <flux:menu>

                                    <flux:menu.item icon="pencil" href="{{ route('suppliers.edit', $supplier) }}">
                                        Edit
                                    </flux:menu.item>

                                    <flux:menu.separator />

                                    <flux:menu.item icon="trash" variant="danger"
                                        wire:click="confirmDelete({{ $supplier->id }})">
                                        Delete
                                    </flux:menu.item>

                                </flux:menu>

                            </flux:dropdown>

                        </flux:table.cell>

                    </flux:table.row>

                @empty

                    <flux:table.row>

                        <flux:table.cell colspan="4" class="text-center">
                            No suppliers found.
                        </flux:table.cell>

                    </flux:table.row>
                @endforelse

            </flux:table.rows>

        </flux:table>

    </flux:card>

    <x-delete-modal name="delete-supplier" heading="Delete Supplier?"
        message="You're about to delete this supplier.<br>This action cannot be reversed." action="destroy" />

</div>
