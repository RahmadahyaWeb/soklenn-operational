<?php

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Service;
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

    public function addDetail()
    {
        $this->details[] = [
            'service_id' => '',
            'qty' => 1,
            'price' => 0,
            'total' => 0,
        ];
    }

    public function removeDetail($index)
    {
        unset($this->details[$index]);

        $this->details = array_values($this->details);
    }

    public function updatedDetails($value, $key)
    {
        [$index, $field] = explode('.', $key);

        if ($field === 'service_id') {

            $service = Service::find($value);

            if ($service) {

                $this->details[$index]['price'] = $service->price;

                $this->calculateDetailTotal($index);

            }

        }

        if ($field === 'qty') {

            $this->calculateDetailTotal($index);

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

                $this->order->update($data);

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
};
