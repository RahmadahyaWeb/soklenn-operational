<?php

namespace App\Http\Controllers;

use App\Models\CustomerMembership;
use App\Models\MembershipReward;

class MembershipCardController
{
    public function __invoke(string $public_token)
    {
        $membership = CustomerMembership::with([
            'customer',
            'rewardClaims.reward',
            'rewardClaims.order',
        ])
            ->where('public_token', $public_token)
            ->firstOrFail();

        $availableRewards = $membership->rewardClaims
            ->whereNull('used_at')
            ->sortByDesc('claimed_at');

        $usedRewards = $membership->rewardClaims
            ->whereNotNull('used_at')
            ->sortByDesc('used_at');

        $nextReward = MembershipReward::query()
            ->where('is_active', true)
            ->where('required_stamp', '>', $membership->stamp)
            ->orderBy('required_stamp')
            ->first();

        $rewardMap = MembershipReward::query()
            ->where('is_active', true)
            ->pluck('name', 'required_stamp');

        return view('membership.card', [
            'membership' => $membership,
            'availableRewards' => $availableRewards,
            'usedRewards' => $usedRewards,
            'nextReward' => $nextReward,
            'rewardMap' => $rewardMap,
        ]);
    }
}
