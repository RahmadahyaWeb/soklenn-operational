<?php

use App\Models\Customer;
use App\Models\CustomerMembership;
use App\Models\MembershipRewardClaim;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Service;
use App\Services\OrderStatusService;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
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
            ->whereHas('reward', function ($query) {
                $query->where('reward_type', '!=', 'tier_upgrade');
            })
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
        return max(
            $this->subtotal
            - $this->discount
            - $this->familyDiscount,
            0
        );
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

    #[Computed()]
    public function familyDiscount()
    {
        if (! $this->customer_id) {
            return 0;
        }

        $customer = Customer::with('membership')
            ->find($this->customer_id);

        if (! $customer?->membership) {
            return 0;
        }

        if ($customer->membership->tier !== 'family') {
            return 0;
        }

        return round($this->subtotal * 10 / 100);
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
                'subtotal' => $this->subtotal,
                'discount' => $this->discount,
                'grand_total' => $this->grandTotal,
                'note' => $this->note,
            ];

            if ($this->order) {

                $this->authorizeUpdate($this->order);

                $this->order->update($data);

                app(OrderStatusService::class)
                    ->transition(
                        $this->order,
                        $this->status
                    );

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

                Flux::toast(
                    heading: 'Success',
                    text: 'Order updated successfully',
                    variant: 'success'
                );

            } else {

                $this->authorizeStore(Order::class);

                $data['public_token'] = Str::uuid()->toString();

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

        CustomerMembership::create([
            'customer_id' => $customer->id,
            'membership_number' => $this->generateMembershipNumber(),
            'member_since' => now(),
        ]);

        $this->customer_id = $customer->id;

        $this->modal('customer-create')->close();

        Flux::toast(
            heading: 'Success',
            text: 'Customer created successfully',
            variant: 'success'
        );
    }

    protected function generateMembershipNumber(): string
    {
        $lastId = CustomerMembership::max('id') + 1;

        return 'SKL-'.str_pad($lastId, 5, '0', STR_PAD_LEFT);
    }

    public function useReward(int $claimId): void
    {
        $this->selected_reward_claim_id = $claimId;

        $this->calculateRewardDiscount();

        $this->modal('reward-selector')->close();
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

    public function removeReward(): void
    {
        $this->selected_reward_claim_id = null;

        $this->calculateRewardDiscount();
    }

    public function openRewardModal(): void
    {
        $this->modal('reward-selector')->show();
    }
};
