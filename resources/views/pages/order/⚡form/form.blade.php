<div class="space-y-6">

    <x-page-header :title="$order ? 'Edit Order' : 'Create Order'" :description="$order
        ? 'Update customer order and service details.'
        : 'Create a new shoe cleaning order for customer services.'" />

    <form wire:submit.prevent="save" class="space-y-6">

        <flux:card class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <flux:field>
                    <flux:label>Customer</flux:label>

                    <div class="flex gap-2">

                        <flux:select wire:model.live="customer_id" class="flex-1">

                            <flux:select.option value="">
                                Walk In Customer
                            </flux:select.option>

                            @foreach ($this->customers as $customer)
                                <flux:select.option :value="$customer->id">
                                    {{ $customer->name }}
                                </flux:select.option>
                            @endforeach

                        </flux:select>

                        <flux:button type="button" variant="primary" wire:click="openCustomerModal">
                            + Customer
                        </flux:button>

                    </div>

                    <flux:error name="customer_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Invoice Number</flux:label>

                    <flux:input wire:model="invoice_number" type="text" />

                    <flux:error name="invoice_number" />
                </flux:field>

            </div>

            @if ($this->availableRewards->count())

                <div class="mt-6 rounded-3xl border border-amber-200 bg-amber-50 p-5">

                    <div class="flex items-center justify-between">

                        <div>

                            <h3 class="text-lg font-bold text-amber-900">
                                🎁 Member Rewards
                            </h3>

                            <p class="text-sm text-amber-700 mt-1">
                                Customer memiliki {{ $this->availableRewards->count() }}
                                reward yang dapat digunakan.
                            </p>

                        </div>

                    </div>

                    <div class="mt-4 grid gap-3">

                        @foreach ($this->availableRewards as $claim)
                            <div
                                class="flex items-center justify-between rounded-2xl border border-amber-200 bg-white p-4">

                                <div>

                                    <div class="font-semibold text-zinc-900">
                                        {{ $claim->reward->name }}
                                    </div>

                                    <div class="text-sm text-zinc-500">
                                        Reward Member
                                    </div>

                                </div>

                                <flux:button size="sm" variant="primary"
                                    wire:click="useReward({{ $claim->id }})">
                                    Gunakan
                                </flux:button>

                            </div>
                        @endforeach

                    </div>

                </div>

            @endif

            @if ($this->selectedReward)
                <div class="mt-4 rounded-3xl border border-green-200 bg-green-50 p-5">

                    <div class="flex items-center justify-between">

                        <div>

                            <div class="flex items-center gap-2">

                                <span class="text-lg">
                                    ✅
                                </span>

                                <div class="font-semibold text-green-900">
                                    Reward Aktif
                                </div>

                            </div>

                            <div class="mt-2 text-lg font-bold text-green-800">
                                {{ $this->selectedReward->reward->name }}
                            </div>

                            <div class="mt-1 text-sm text-green-700">
                                Reward akan diterapkan ke transaksi ini.
                            </div>

                        </div>

                        @if ($this->selectedReward && in_array($status, ['pending']))
                            <flux:button size="sm" variant="danger" wire:click="removeReward">
                                Batalkan
                            </flux:button>
                        @endif

                    </div>

                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

                <flux:field>
                    <flux:label>Received At</flux:label>

                    <flux:input wire:model="received_at" type="datetime-local" />

                    <flux:error name="received_at" />
                </flux:field>

                <flux:field>
                    <flux:label>Completed At</flux:label>

                    <flux:input wire:model="completed_at" type="datetime-local" />

                    <flux:error name="completed_at" />
                </flux:field>

                @if ($order)
                    <flux:field>
                        <flux:label>Status</flux:label>

                        <flux:select wire:model="status">

                            @foreach ($this->availableStatuses as $value => $label)
                                <flux:select.option :value="$value">
                                    {{ $label }}
                                </flux:select.option>
                            @endforeach

                        </flux:select>

                        <flux:error name="status" />
                    </flux:field>
                @endif

                <flux:field>
                    <flux:label>Discount</flux:label>

                    <flux:input wire:model.live="discount" type="number" min="0" />

                    <flux:error name="discount" />
                </flux:field>

            </div>

            <flux:field>
                <flux:label>Note</flux:label>

                <flux:textarea wire:model="note" rows="3" />

                <flux:error name="note" />
            </flux:field>

        </flux:card>

        <flux:card class="space-y-6">

            <div class="flex items-center justify-between">

                <div>
                    <flux:heading size="lg">
                        Services
                    </flux:heading>

                    <flux:text>
                        Add services included in this order.
                    </flux:text>
                </div>

                <flux:button type="button" variant="primary" wire:click="addDetail">
                    Add Service
                </flux:button>

            </div>

            <div class="space-y-4">

                @foreach ($details as $index => $detail)
                    <div wire:key="detail-{{ $index }}" class="border rounded-xl p-5 space-y-4">

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

                            <div class="md:col-span-5">

                                <flux:field>
                                    <flux:label>Service</flux:label>

                                    <flux:select wire:model.live="details.{{ $index }}.service_id">

                                        <flux:select.option value="">
                                            Select Service
                                        </flux:select.option>

                                        @foreach ($this->services as $service)
                                            <flux:select.option :value="$service->id">
                                                {{ $service->name }}
                                            </flux:select.option>
                                        @endforeach

                                    </flux:select>

                                    <flux:error name="details.{{ $index }}.service_id" />
                                </flux:field>

                            </div>

                            <div class="md:col-span-2">

                                <flux:field>
                                    <flux:label>Qty</flux:label>

                                    <flux:input wire:model.live="details.{{ $index }}.qty" type="number"
                                        min="1" />

                                    <flux:error name="details.{{ $index }}.qty" />
                                </flux:field>

                            </div>

                            <div class="md:col-span-2">

                                <flux:field>
                                    <flux:label>Price</flux:label>

                                    <flux:input wire:model="details.{{ $index }}.price" type="text"
                                        readonly />
                                </flux:field>

                            </div>

                            <div class="md:col-span-2">

                                <flux:field>
                                    <flux:label>Total</flux:label>

                                    <flux:input wire:model="details.{{ $index }}.total" type="text"
                                        readonly />
                                </flux:field>

                            </div>

                            <div class="md:col-span-1 flex items-start pt-6">

                                <flux:button type="button" variant="danger"
                                    wire:click="removeDetail({{ $index }})">
                                    Remove
                                </flux:button>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

            <div class="border-t pt-6">

                <div class="max-w-sm ml-auto space-y-3">

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-zinc-500">
                            Subtotal
                        </span>

                        <span>
                            Rp {{ number_format($this->subtotal, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-zinc-500">
                            Discount
                        </span>

                        <span>
                            Rp {{ number_format($discount, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="border-t pt-3 flex items-center justify-between text-lg font-semibold">
                        <span>
                            Grand Total
                        </span>

                        <span>
                            Rp {{ number_format(max($this->grandTotal, 0), 0, ',', '.') }}
                        </span>
                    </div>

                </div>

            </div>

            <div class="flex justify-end">

                <flux:button type="submit" variant="primary">
                    {{ $order ? 'Update' : 'Create' }}
                </flux:button>

            </div>

        </flux:card>

    </form>

    <flux:modal name="customer-create" class="md:w-[500px]">

        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    Add Customer
                </flux:heading>

                <flux:text class="mt-2">
                    Create a new customer without leaving this page.
                </flux:text>
            </div>

            <div class="space-y-4">

                <flux:field>
                    <flux:label>Name</flux:label>

                    <flux:input wire:model="customer_name" type="text" />

                    <flux:error name="customer_name" />
                </flux:field>

                <flux:field>
                    <flux:label>Phone</flux:label>

                    <flux:input wire:model="customer_phone" type="text" />

                    <flux:error name="customer_phone" />
                </flux:field>

            </div>

            <div class="flex justify-end gap-2">

                <flux:button type="button" variant="ghost"
                    wire:click="$js(() => Flux.modal('customer-create').close())">
                    Cancel
                </flux:button>

                <flux:button type="button" variant="primary" wire:click="createCustomer">
                    Save Customer
                </flux:button>

            </div>

        </div>

    </flux:modal>

</div>
