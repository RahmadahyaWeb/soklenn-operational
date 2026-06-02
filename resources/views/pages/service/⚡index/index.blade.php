<div>

    <x-page-header title="Services" description="Manage shoe cleaning services and pricing for customer orders."
        button-label="Add Service" :button-href="route('services.create')" />

    <flux:card>

        <flux:table :paginate="$this->services">

            <flux:table.columns>
                <flux:table.column>Service</flux:table.column>
                <flux:table.column>Price</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Description</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>

                @forelse ($this->services as $service)
                    <flux:table.row :key="$service->id">

                        <flux:table.cell>
                            {{ $service->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            Rp {{ number_format($service->price, 0, ',', '.') }}
                        </flux:table.cell>

                        <flux:table.cell>
                            @if ($service->is_active)
                                <flux:badge color="green">
                                    Active
                                </flux:badge>
                            @else
                                <flux:badge color="red">
                                    Inactive
                                </flux:badge>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $service->description ?? '-' }}
                        </flux:table.cell>

                        <flux:table.cell align="end">

                            <flux:dropdown>

                                <flux:button variant="ghost" size="sm" icon:trailing="ellipsis-horizontal" />

                                <flux:menu>

                                    <flux:menu.item icon="pencil" href="{{ route('services.edit', $service) }}">
                                        Edit
                                    </flux:menu.item>

                                    <flux:menu.separator />

                                    <flux:menu.item icon="trash" variant="danger"
                                        wire:click="confirmDelete({{ $service->id }})">
                                        Delete
                                    </flux:menu.item>

                                </flux:menu>

                            </flux:dropdown>

                        </flux:table.cell>

                    </flux:table.row>

                @empty

                    <flux:table.row>

                        <flux:table.cell colspan="5" class="text-center">
                            No services found.
                        </flux:table.cell>

                    </flux:table.row>
                @endforelse

            </flux:table.rows>

        </flux:table>

    </flux:card>

    <x-delete-modal name="delete-service" heading="Delete Service?"
        message="You're about to delete this service.<br>This action cannot be reversed." action="destroy" />

</div>
