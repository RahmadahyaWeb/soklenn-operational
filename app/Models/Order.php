<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'invoice_number',
        'received_at',
        'completed_at',
        'status',
        'subtotal',
        'discount',
        'grand_total',
        'note',
        'membership_processed_at',
        'membership_reward_claim_id',
        'public_token',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'completed_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'membership_processed_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function income()
    {
        return $this->hasOne(Income::class);
    }

    public function membershipRewardClaim()
    {
        return $this->belongsTo(
            MembershipRewardClaim::class
        );
    }
}
