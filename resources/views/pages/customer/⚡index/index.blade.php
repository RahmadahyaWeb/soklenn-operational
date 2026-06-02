<div>

    <x-page-header title="Customers" description="Manage customer information and order relationships."
        button-label="Add Customer" :button-href="route('customers.create')" />

    <flux:card>

        <flux:table :paginate="$this->customers">

            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Phone</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>

                @forelse ($this->customers as $customer)
                    <flux:table.row :key="$customer->id">

                        <flux:table.cell>
                            {{ $customer->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $customer->phone ?? '-' }}
                        </flux:table.cell>

                        <flux:table.cell align="end">

                            <flux:dropdown>

                                <flux:button variant="ghost" size="sm" icon:trailing="ellipsis-horizontal" />

                                <flux:menu>

                                    <flux:menu.item icon="pencil" href="{{ route('customers.edit', $customer) }}">
                                        Edit
                                    </flux:menu.item>

                                    <flux:menu.separator />

                                    <flux:menu.item icon="trash" variant="danger"
                                        wire:click="confirmDelete({{ $customer->id }})">
                                        Delete
                                    </flux:menu.item>

                                </flux:menu>

                            </flux:dropdown>

                        </flux:table.cell>

                    </flux:table.row>

                @empty

                    <flux:table.row>

                        <flux:table.cell colspan="3" class="text-center">
                            No customers found.
                        </flux:table.cell>

                    </flux:table.row>
                @endforelse

            </flux:table.rows>

        </flux:table>

    </flux:card>

    <x-delete-modal name="delete-customer" heading="Delete Customer?"
        message="You're about to delete this customer.<br>This action cannot be reversed." action="destroy" />

</div>
