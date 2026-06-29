<?php

namespace App\Http\Controllers;

use App\Models\Order;

class PublicInvoiceController extends Controller
{
    public function __invoke(string $token)
    {
        $order = Order::query()
            ->with([
                'customer.membership',
                'membershipRewardClaim.reward',
                'details.service',
            ])
            ->where('public_token', $token)
            ->firstOrFail();

        return view('sections.invoice', [
            'order' => $order,
        ]);
    }
}
