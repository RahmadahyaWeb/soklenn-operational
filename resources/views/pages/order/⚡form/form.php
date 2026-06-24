<?php

use App\Models\Customer;
use App\Models\MembershipRewardClaim;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Service;
use App\Services\MembershipService;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    use AuthorizesCrud, AuthorizesRequests;

    public ?Order $order = null;

    public $customer_id;

    public $invoice_number;

    public $received_at;

    public $completed_at;

    public $status = 'pending';

    public $discount = 0;

    public $note;

    public $details = [];

    public $customer_name;

    public $customer_phone;

    public $selected_reward_claim_id;

    public function mount(?Order $order = null)
    {
        if ($order && $order->exists) {

            $this->authorizeUpdate($order);

            $this->order = $order;

            $this->customer_id = $order->customer_id;
            $this->invoice_number = $order->invoice_number;
            $this->received_at = $order->received_at?->format('Y-m-d\TH:i');
            $this->completed_at = $order->completed_at?->format('Y-m-d\TH:i');
            $this->status = $order->status;
            $this->discount = $order->discount;
            $this->note = $order->note;
            $this->selected_reward_claim_id =
    $order->membership_reward_claim_id;

            $this->details = $order->details
                ->map(function ($detail) {
                    return [
                        'service_id' => $detail->service_id,
                        'qty' => $detail->qty,
                        'price' => $detail->price,
                        'total' => $detail->total,
                    ];
                })
                ->toArray();

            if ($this->selected_reward_claim_id) {
                $this->calculateRewardDiscount();
            }

        } else {

            $this->authorizeStore(Order::class);

            $this->invoice_number = 'INV-'.strtoupper(Str::random(8));

            $this->received_at = now()->format('Y-m-d\TH:i');

            $this->details[] = [
                'service_id' => '',
                'qty' => 1,
                'price' => 0,
                'total' => 0,
            ];

        }
    }

    #[Computed()]
    public function customers()
    {
        return Customer::orderBy('name')->get();
    }

    #[Computed()]
    public function services()
    {
        return Service::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function availableRewards()
    {
        if (! $this->customer_id) {
            return collect();
        }

        $customer = Customer::with([
            'membership.rewardClaims.reward',
        ])->find($this->customer_id);

        if (! $customer?->membership) {
            return collect();
        }

        return MembershipRewardClaim::query()
            ->with('reward')
            ->where(
                'customer_membership_id',
                $customer->membership->id
            )
            ->whereNull('used_at')
            ->whereDoesntHave('orders', function ($query) {

                $query->whereIn('status', [
                    'pending',
                    'washing',
                    'drying',
                    'finished',
                ]);

                if ($this->order) {

                    $query->where('id', '!=', $this->order->id);

                }

            })
            ->get();
    }

    #[Computed()]
    public function selectedReward()
    {
        if (! $this->selected_reward_claim_id) {
            return null;
        }

        return MembershipRewardClaim::with('reward')
            ->find($this->selected_reward_claim_id);
    }

    public function addDetail()
    {
        $this->details[] = [
            'service_id' => '',
            'qty' => 1,
            'price' => 0,
            'total' => 0,
        ];

        $this->calculateRewardDiscount();
    }

    public function removeDetail($index)
    {
        unset($this->details[$index]);

        $this->details = array_values($this->details);

        $this->calculateRewardDiscount();
    }

    public function updatedDetails($value, $key)
    {
        [$index, $field] = explode('.', $key);

        if ($field === 'service_id') {

            $service = Service::find($value);

            if ($service) {

                $this->details[$index]['price'] = $service->price;

                $this->calculateDetailTotal($index);

                $this->calculateRewardDiscount();

            }

        }

        if ($field === 'qty') {

            $this->calculateDetailTotal($index);

            $this->calculateRewardDiscount();

        }
    }

    public function calculateDetailTotal($index)
    {
        $qty = (int) ($this->details[$index]['qty'] ?? 0);

        $price = (float) ($this->details[$index]['price'] ?? 0);

        $this->details[$index]['total'] = $qty * $price;
    }

    #[Computed()]
    public function subtotal()
    {
        return collect($this->details)->sum('total');
    }

    #[Computed()]
    public function grandTotal()
    {
        return $this->subtotal - $this->discount;
    }

    #[Computed()]
    public function availableStatuses()
    {
        if (! $this->order) {

            return [
                'pending' => 'Pending',
            ];

        }

        return match ($this->order->status) {

            'pending' => [
                'pending' => 'Pending',
                'washing' => 'Washing',
                'cancelled' => 'Cancelled',
            ],

            'washing' => [
                'washing' => 'Washing',
                'drying' => 'Drying',
            ],

            'drying' => [
                'drying' => 'Drying',
                'finished' => 'Finished',
            ],

            'finished' => [
                'finished' => 'Finished',
                'picked_up' => 'Picked Up',
            ],

            'picked_up' => [
                'picked_up' => 'Picked Up',
            ],

            'cancelled' => [
                'cancelled' => 'Cancelled',
            ],

            default => [],
        };
    }

    public function save()
    {
        $this->validate([
            'customer_id' => [
                'nullable',
                'exists:customers,id',
            ],

            'invoice_number' => [
                'required',
                'string',
                'max:255',
                $this->order
                    ? 'unique:orders,invoice_number,'.$this->order->id
                    : 'unique:orders,invoice_number',
            ],

            'received_at' => [
                'required',
                'date',
            ],

            'completed_at' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                'in:pending,washing,drying,finished,picked_up,cancelled',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'note' => [
                'nullable',
                'string',
            ],

            'details' => [
                'required',
                'array',
                'min:1',
            ],

            'details.*.service_id' => [
                'required',
                'exists:services,id',
            ],

            'details.*.qty' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        return $this->transaction(function () {

            $data = [
                'customer_id' => $this->customer_id,
                'membership_reward_claim_id' => $this->selected_reward_claim_id,
                'invoice_number' => $this->invoice_number,
                'received_at' => $this->received_at,
                'completed_at' => $this->completed_at,
                'status' => $this->status,
                'subtotal' => $this->subtotal,
                'discount' => $this->discount,
                'grand_total' => $this->grandTotal,
                'note' => $this->note,
            ];

            if ($this->order) {

                $this->authorizeUpdate($this->order);

                $oldStatus = $this->order->status;

                $allowedTransitions = [
                    'pending' => [
                        'pending',
                        'washing',
                        'cancelled',
                    ],

                    'washing' => [
                        'washing',
                        'drying',
                    ],

                    'drying' => [
                        'drying',
                        'finished',
                    ],

                    'finished' => [
                        'finished',
                        'picked_up',
                    ],

                    'picked_up' => [
                        'picked_up',
                    ],

                    'cancelled' => [
                        'cancelled',
                    ],
                ];

                if (
                    ! in_array(
                        $this->status,
                        $allowedTransitions[$oldStatus] ?? []
                    )
                ) {
                    throw ValidationException::withMessages([
                        'status' => 'Perubahan status tidak valid.',
                    ]);
                }

                if (
                    $this->order->status !== 'pending'
                    && $this->status === 'cancelled'
                ) {
                    throw ValidationException::withMessages([
                        'status' => 'Order yang sudah masuk proses pencucian tidak dapat dibatalkan.',
                    ]);
                }

                $this->order->update($data);

                if (
                    $this->order->status === 'washing'
                    && $this->selected_reward_claim_id
                ) {

                    $this->consumeReward();

                }

                $this->order->details()->delete();

                foreach ($this->details as $detail) {

                    OrderDetail::create([
                        'order_id' => $this->order->id,
                        'service_id' => $detail['service_id'],
                        'qty' => $detail['qty'],
                        'price' => $detail['price'],
                        'total' => $detail['total'],
                    ]);

                }

                if (
                    $oldStatus !== 'washing'
                    && $this->order->status === 'washing'
                    && is_null($this->order->membership_processed_at)
                ) {

                    app(MembershipService::class)
                        ->addStamp($this->order->customer_id);

                    $this->order->update([
                        'membership_processed_at' => now(),
                    ]);

                    $this->consumeReward();

                }

                Flux::toast(
                    heading: 'Success',
                    text: 'Order updated successfully',
                    variant: 'success'
                );

            } else {

                $this->authorizeStore(Order::class);

                $order = Order::create($data);

                foreach ($this->details as $detail) {

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'service_id' => $detail['service_id'],
                        'qty' => $detail['qty'],
                        'price' => $detail['price'],
                        'total' => $detail['total'],
                    ]);

                }

                Flux::toast(
                    heading: 'Success',
                    text: 'Order created successfully',
                    variant: 'success'
                );

            }

            $this->redirect(route('orders.index'), navigate: true);

        });
    }

    public function openCustomerModal()
    {
        $this->reset([
            'customer_name',
            'customer_phone',
        ]);

        $this->modal('customer-create')->show();
    }

    public function createCustomer()
    {
        $this->validate([
            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],

            'customer_phone' => [
                'nullable',
                'string',
                'max:50',
            ],
        ]);

        $customer = Customer::create([
            'name' => $this->customer_name,
            'phone' => $this->customer_phone,
        ]);

        $this->customer_id = $customer->id;

        $this->modal('customer-create')->close();

        Flux::toast(
            heading: 'Success',
            text: 'Customer created successfully',
            variant: 'success'
        );
    }

    public function useReward(int $claimId)
    {
        $this->selected_reward_claim_id = $claimId;

        $this->calculateRewardDiscount();
    }

    public function calculateRewardDiscount(): void
    {
        $this->discount = 0;

        if (! $this->selected_reward_claim_id) {
            return;
        }

        $claim = MembershipRewardClaim::with('reward')
            ->find($this->selected_reward_claim_id);

        if (! $claim) {
            return;
        }

        if ($claim->reward->reward_type === 'discount_percentage') {

            $this->discount = round(
                ($this->subtotal * $claim->reward->reward_value) / 100
            );

            return;
        }

        if ($claim->reward->reward_type === 'discount_fixed') {

            $this->discount = min(
                (float) $claim->reward->reward_value,
                (float) $this->subtotal
            );

            return;
        }
    }

    protected function consumeReward(): void
    {
        if (! $this->selected_reward_claim_id) {
            return;
        }

        $claim = MembershipRewardClaim::find(
            $this->selected_reward_claim_id
        );

        if (! $claim) {
            return;
        }

        if ($claim->used_at) {
            return;
        }

        $claim->update([
            'order_id' => $this->order->id,
            'used_at' => now(),
        ]);
    }

    public function removeReward(): void
    {
        $this->selected_reward_claim_id = null;

        $this->calculateRewardDiscount();
    }
};
