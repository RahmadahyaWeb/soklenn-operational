<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembershipRewardClaim extends Model
{
    protected $fillable = [
        'customer_membership_id',
        'membership_reward_id',
        'order_id',
        'claimed_at',
        'used_at',
    ];

    protected $casts = [
        'claimed_at' => 'datetime',
    ];

    public function membership(): BelongsTo
    {
        return $this->belongsTo(CustomerMembership::class, 'customer_membership_id');
    }

    public function reward(): BelongsTo
    {
        return $this->belongsTo(MembershipReward::class, 'membership_reward_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orders()
    {
        return $this->hasMany(
            Order::class
        );
    }
}
