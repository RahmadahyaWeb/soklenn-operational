<?php

namespace App\Services;

use App\Models\MembershipRewardClaim;
use App\Models\Order;
use Illuminate\Validation\ValidationException;

class OrderStatusService
{
    public function transition(
        Order $order,
        string $status
    ): void {
        $this->validateTransition(
            $order,
            $status
        );

        if ($order->status === $status) {
            return;
        }

        $oldStatus = $order->status;

        $order->update([
            'status' => $status,
        ]);

        $this->processMembership(
            $order,
            $oldStatus
        );
    }

    protected function validateTransition(
        Order $order,
        string $status
    ): void {

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
                $status,
                $allowedTransitions[$order->status] ?? []
            )
        ) {

            throw ValidationException::withMessages([
                'status' => 'Perubahan status tidak valid.',
            ]);

        }

        if (
            $order->status !== 'pending'
            && $status === 'cancelled'
        ) {

            throw ValidationException::withMessages([
                'status' => 'Order yang sudah masuk proses pencucian tidak dapat dibatalkan.',
            ]);

        }

    }

    protected function processMembership(
        Order $order,
        string $oldStatus
    ): void {
        if (
            $oldStatus !== 'washing'
            && $order->status === 'washing'
            && is_null($order->membership_processed_at)
        ) {

            app(MembershipService::class)
                ->addStamp($order->customer_id);

            $order->update([
                'membership_processed_at' => now(),
            ]);

            $this->consumeReward($order);

        }
    }

    protected function consumeReward(
        Order $order
    ): void {
        if (! $order->membership_reward_claim_id) {
            return;
        }

        $claim = MembershipRewardClaim::find(
            $order->membership_reward_claim_id
        );

        if (! $claim) {
            return;
        }

        if ($claim->used_at) {
            return;
        }

        $claim->update([
            'order_id' => $order->id,
            'used_at' => now(),
        ]);
    }
}
