<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CustomerMembership extends Model
{
    protected $fillable = [
        'customer_id',
        'membership_number',
        'tier',
        'stamp',
        'total_orders',
        'total_spent',
        'member_since',
        'family_since',
        'public_token',
        'member_code',
    ];

    protected $casts = [
        'member_since' => 'datetime',
        'family_since' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function rewardClaims(): HasMany
    {
        return $this->hasMany(MembershipRewardClaim::class);
    }

    public function isFamily(): bool
    {
        return $this->tier === 'family';
    }

    protected static function booted(): void
    {
        static::creating(function ($membership) {

            if (! $membership->public_token) {
                $membership->public_token = Str::uuid();
            }

        });

        static::created(function ($membership) {

            if (! $membership->member_code) {

                $membership->updateQuietly([
                    'member_code' => sprintf(
                        'SKL-%06d',
                        $membership->id
                    ),
                ]);

            }

        });
    }

    public function availableRewards()
    {
        return $this->rewardClaims()
            ->whereNull('used_at');
    }
}
