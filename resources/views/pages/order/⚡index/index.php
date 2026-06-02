<?php

use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\Order;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Title('Orders')] class extends Component
{
    use AuthorizesCrud;
    use WithoutUrlPagination, WithPagination;

    public $deleteId;

    public $search = '';

    public $status = '';

    public function mount()
    {
        $this->authorizeIndex(Order::class);
    }

    #[Computed()]
    public function orders()
    {
        return Order::with([
            'customer',
            'details.service',
            'income',
        ])
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('invoice_number', 'like', '%'.$this->search.'%')
                        ->orWhereHas('customer', function ($query) {
                            $query->where('name', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(10);
    }

    public function confirmDelete(int $id): void
    {
        $order = Order::findOrFail($id);

        $this->authorizeDelete($order);

        $this->deleteId = $id;

        $this->modal('delete-order')->show();
    }

    public function destroy()
    {
        $this->transaction(function () {

            $order = Order::findOrFail($this->deleteId);

            $this->authorizeDelete($order);

            $order->delete();

            Flux::toast(
                heading: 'Success',
                text: 'Order deleted successfully',
                variant: 'success'
            );

            $this->modal('delete-order')->close();

        });
    }

    public function updateStatus(int $id, string $status)
    {
        return $this->transaction(function () use ($id, $status) {

            $order = Order::with('income')->findOrFail($id);

            $this->authorizeUpdate($order);

            if ($order->status === 'picked_up') {

                Flux::toast(
                    heading: 'Warning',
                    text: 'Picked up orders cannot be updated',
                    variant: 'warning'
                );

                return;
            }

            $order->update([
                'status' => $status,
            ]);

            if (
                $status === 'picked_up'
                && ! $order->income
            ) {

                $category = IncomeCategory::where('name', 'Order Income')
                    ->first();

                if ($category) {

                    Income::create([
                        'income_category_id' => $category->id,
                        'order_id' => $order->id,
                        'transaction_date' => now(),
                        'amount' => $order->grand_total,
                        'title' => 'Income from Order '.$order->invoice_number,
                        'description' => null,
                    ]);

                }

            }

            unset($this->orders);

            Flux::toast(
                heading: 'Success',
                text: 'Order status updated successfully',
                variant: 'success'
            );

        });
    }

    public function statusBadgeColor(string $status): string
    {
        return match ($status) {
            'pending' => 'zinc',
            'washing' => 'blue',
            'drying' => 'yellow',
            'finished' => 'green',
            'picked_up' => 'emerald',
            'cancelled' => 'red',
            default => 'zinc',
        };
    }

    public function statuses(): array
    {
        return [
            'pending',
            'washing',
            'drying',
            'finished',
            'picked_up',
            'cancelled',
        ];
    }
};
