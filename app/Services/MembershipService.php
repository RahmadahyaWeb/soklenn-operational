<?php

namespace App\Services;

use App\Models\CustomerMembership;
use App\Models\MembershipReward;
use App\Models\MembershipRewardClaim;

class MembershipService
{
    public function addStamp(int $customerId): void
    {
        $membership = CustomerMembership::where(
            'customer_id',
            $customerId
        )->first();

        if (! $membership) {
            return;
        }

        $membership->increment('stamp');

        $membership->refresh();

        $this->processRewards($membership);
    }

    protected function processRewards(CustomerMembership $membership): void
    {
        $rewards = MembershipReward::query()
            ->where('is_active', true)
            ->where('required_stamp', '<=', $membership->stamp)
            ->get();

        foreach ($rewards as $reward) {

            $alreadyClaimed = MembershipRewardClaim::query()
                ->where('customer_membership_id', $membership->id)
                ->where('membership_reward_id', $reward->id)
                ->exists();

            if ($alreadyClaimed) {
                continue;
            }

            MembershipRewardClaim::create([
                'customer_membership_id' => $membership->id,
                'membership_reward_id' => $reward->id,
                'claimed_at' => now(),
            ]);

            if (
                $reward->reward_type === 'tier_upgrade'
                && $reward->reward_value === 'family'
                && $membership->tier !== 'family'
            ) {

                $membership->update([
                    'tier' => 'family',
                    'family_since' => now(),
                ]);

            }

        }
    }
}
