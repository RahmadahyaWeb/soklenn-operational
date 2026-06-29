<div>

    <x-page-header title="Customers" description="Manage customer information and order relationships."
        button-label="Add Customer" :button-href="route('customers.create')" />

    <flux:card>

        <div class="mb-6 grid gap-4 md:grid-cols-4">

            <flux:field>

                <flux:label>
                    Search
                </flux:label>

                <flux:input wire:model.live.debounce.300ms="search" placeholder="Name, phone or member code..." />

            </flux:field>

            <flux:field>

                <flux:label>
                    Tier
                </flux:label>

                <flux:select wire:model.live="tier">

                    <flux:select.option value="">
                        All Tier
                    </flux:select.option>

                    <flux:select.option value="regular">
                        Regular
                    </flux:select.option>

                    <flux:select.option value="family">
                        Family
                    </flux:select.option>

                </flux:select>

            </flux:field>

            <flux:field>

                <flux:label>
                    Reward
                </flux:label>

                <flux:select wire:model.live="reward">

                    <flux:select.option value="">
                        All
                    </flux:select.option>

                    <flux:select.option value="available">
                        Has Reward
                    </flux:select.option>

                    <flux:select.option value="empty">
                        No Reward
                    </flux:select.option>

                </flux:select>

            </flux:field>

            <flux:field>

                <flux:label>
                    Stamp
                </flux:label>

                <flux:select wire:model.live="stamp">

                    <flux:select.option value="">
                        All
                    </flux:select.option>

                    <flux:select.option value="0">
                        0 Stamp
                    </flux:select.option>

                    <flux:select.option value="1-4">
                        1 - 4
                    </flux:select.option>

                    <flux:select.option value="5-9">
                        5 - 9
                    </flux:select.option>

                    <flux:select.option value="10-14">
                        10 - 14
                    </flux:select.option>

                    <flux:select.option value="15+">
                        15+
                    </flux:select.option>

                </flux:select>

            </flux:field>

        </div>

        <flux:table :paginate="$this->customers">

            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Phone</flux:table.column>
                <flux:table.column>Member Code</flux:table.column>
                <flux:table.column>Stamp</flux:table.column>
                <flux:table.column>Tier</flux:table.column>
                <flux:table.column>Reward</flux:table.column>
                <flux:table.column>Membership URL</flux:table.column>
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

                        <flux:table.cell>
                            {{ $customer->membership?->member_code ?? '-' }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $customer->membership?->stamp ?? 0 }}
                        </flux:table.cell>

                        <flux:table.cell>

                            @if ($customer->membership?->tier === 'family')
                                <flux:badge color="amber">
                                    Family
                                </flux:badge>
                            @else
                                <flux:badge color="zinc">
                                    Regular
                                </flux:badge>
                            @endif

                        </flux:table.cell>

                        <flux:table.cell>

                            @if ($customer->membership)
                                <a href="{{ route('membership.card', $customer->membership->public_token) }}"
                                    target="_blank" class="text-xs text-blue-600 hover:underline">

                                    Open Card

                                </a>
                            @else
                                -
                            @endif

                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $customer->membership?->rewardClaims?->whereNull('used_at')->count() ?? 0 }}
                        </flux:table.cell>

                        <flux:table.cell align="end">

                            <flux:dropdown>

                                <flux:button variant="ghost" size="sm" icon:trailing="ellipsis-horizontal" />

                                <flux:menu>

                                    <flux:menu.item icon="star" wire:click="viewMembership({{ $customer->id }})">
                                        Membership
                                    </flux:menu.item>

                                    <flux:menu.item icon="clipboard-document"
                                        wire:click="copyMembership({{ $customer->id }})">

                                        Copy Membership Info

                                    </flux:menu.item>

                                    <flux:menu.separator />

                                    <flux:menu.separator />

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

                        <flux:table.cell colspan="8" class="text-center">
                            No customers found.
                        </flux:table.cell>

                    </flux:table.row>
                @endforelse

            </flux:table.rows>

        </flux:table>

    </flux:card>

    <flux:modal name="customer-membership" class="md:w-[700px]">

        <div class="space-y-6">

            <div>

                <h2 class="text-xl font-semibold">
                    Membership Information
                </h2>

                <p class="text-sm text-zinc-500">
                    Customer membership, stamp, and rewards.
                </p>

            </div>

            @if ($selectedCustomer)

                <flux:card>

                    <div class="grid grid-cols-2 gap-6">

                        <div>

                            <div class="text-sm text-zinc-500">
                                Customer
                            </div>

                            <div class="font-medium">
                                {{ $selectedCustomer->name }}
                            </div>

                        </div>

                        <div>

                            <div class="text-sm text-zinc-500">
                                Phone
                            </div>

                            <div class="font-medium">
                                {{ $selectedCustomer->phone ?: '-' }}
                            </div>

                        </div>

                    </div>

                </flux:card>

                <flux:card>

                    <div class="grid grid-cols-3 gap-6">

                        <div>

                            <div class="text-sm text-zinc-500">
                                Tier
                            </div>

                            <div class="font-semibold">
                                {{ ucfirst($selectedCustomer->membership?->tier ?? 'regular') }}
                            </div>

                        </div>

                        <div>

                            <div class="text-sm text-zinc-500">
                                Stamp
                            </div>

                            <div class="font-semibold">
                                {{ $selectedCustomer->membership?->stamp ?? 0 }}
                            </div>

                        </div>

                        <div>

                            <div class="text-sm text-zinc-500">
                                Progress
                            </div>

                            <div class="font-semibold">
                                {{ min($selectedCustomer->membership?->stamp ?? 0, 15) }}/15
                            </div>

                        </div>

                    </div>

                </flux:card>

                <flux:card>

                    <div class="mb-4">

                        <h3 class="font-semibold">
                            Membership Progress
                        </h3>

                    </div>

                    @if ($this->nextReward)
                        <div class="space-y-2">

                            <div>

                                Current Stamp:
                                <strong>
                                    {{ $selectedCustomer->membership->stamp }}
                                </strong>

                            </div>

                            <div>

                                Next Reward:
                                <strong>
                                    {{ $this->nextReward->name }}
                                </strong>

                            </div>

                            <div>

                                Need
                                <strong>
                                    {{ $this->nextReward->required_stamp - $selectedCustomer->membership->stamp }}
                                </strong>
                                more stamp(s)

                            </div>

                        </div>
                    @else
                        <div class="text-green-600 font-medium">

                            🎉 Customer sudah mencapai reward tertinggi.

                        </div>
                    @endif

                </flux:card>

                <flux:card>

                    <div class="mb-4">

                        <h3 class="font-semibold">
                            Available Rewards
                        </h3>

                    </div>

                    <div class="space-y-2">

                        @forelse($selectedCustomer->membership?->rewardClaims
                            ?->whereNull('used_at')
                            ?? collect()
                        as $reward)
                            <div class="flex items-center justify-between rounded-xl border p-3">

                                <div>

                                    <div class="font-medium">
                                        {{ $reward->reward->name }}
                                    </div>

                                    <div class="text-sm text-zinc-500">
                                        Earned at
                                        {{ $reward->claimed_at?->format('d M Y H:i') }}
                                    </div>

                                </div>

                                <flux:badge color="green">
                                    Available
                                </flux:badge>

                            </div>

                        @empty

                            <div class="text-sm text-zinc-500">
                                No available rewards.
                            </div>
                        @endforelse

                    </div>

                </flux:card>

                <flux:card>

                    <div class="mb-4">

                        <h3 class="font-semibold">
                            Reward History
                        </h3>

                        <p class="text-sm text-zinc-500">
                            Riwayat reward yang diperoleh dan digunakan customer.
                        </p>

                    </div>

                    <div class="space-y-3">

                        @forelse ($selectedCustomer->membership?->rewardClaims
                ?->sortByDesc('claimed_at')
                ?? collect()
            as $claim)
                            <div class="rounded-xl border p-4">

                                <div class="flex items-start justify-between">

                                    <div>

                                        <div class="font-medium">
                                            {{ $claim->reward->name }}
                                        </div>

                                        <div class="text-sm text-zinc-500 mt-1">

                                            Diperoleh:
                                            {{ $claim->claimed_at?->format('d M Y H:i') }}

                                        </div>

                                        @if ($claim->used_at)
                                            <div class="text-sm text-zinc-500">

                                                Digunakan:
                                                {{ $claim->used_at?->format('d M Y H:i') }}

                                            </div>
                                        @endif

                                        @if ($claim->order)
                                            <div class="text-sm text-zinc-500">

                                                Order:
                                                {{ $claim->order->invoice_number }}

                                            </div>
                                        @endif

                                    </div>

                                    @if ($claim->used_at)
                                        <flux:badge color="zinc">
                                            Used
                                        </flux:badge>
                                    @else
                                        <flux:badge color="green">
                                            Available
                                        </flux:badge>
                                    @endif

                                </div>

                            </div>

                        @empty

                            <div class="text-sm text-zinc-500">
                                Belum ada riwayat reward.
                            </div>
                        @endforelse

                    </div>

                </flux:card>

            @endif

        </div>

    </flux:modal>

    <x-delete-modal name="delete-customer" heading="Delete Customer?"
        message="You're about to delete this customer.<br>This action cannot be reversed." action="destroy" />

</div>
