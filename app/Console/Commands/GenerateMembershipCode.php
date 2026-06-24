<?php

namespace App\Console\Commands;

use App\Models\CustomerMembership;
use Illuminate\Console\Command;

class GenerateMembershipCode extends Command
{
    protected $signature = 'membership:generate-code';

    protected $description = 'Generate member code for existing memberships';

    public function handle(): int
    {
        CustomerMembership::query()
            ->whereNull('member_code')
            ->orderBy('id')
            ->chunk(100, function ($memberships) {

                foreach ($memberships as $membership) {

                    $membership->update([
                        'member_code' => sprintf(
                            'SKL-%06d',
                            $membership->id
                        ),
                    ]);

                }

            });

        $this->info('Membership code generated.');

        return self::SUCCESS;
    }
}
