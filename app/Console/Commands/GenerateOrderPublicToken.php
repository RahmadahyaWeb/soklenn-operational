<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateOrderPublicToken extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'orders:generate-public-token';

    /**
     * The console command description.
     */
    protected $description = 'Generate public token for orders that do not have one';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $orders = Order::query()
            ->whereNull('public_token')
            ->orWhere('public_token', '')
            ->get();

        if ($orders->isEmpty()) {
            $this->info('All orders already have public tokens.');

            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($orders->count());

        $bar->start();

        foreach ($orders as $order) {
            $order->update([
                'public_token' => Str::uuid()->toString(),
            ]);

            $bar->advance();
        }

        $bar->finish();

        $this->newLine(2);
        $this->info("Generated {$orders->count()} public token(s).");

        return self::SUCCESS;
    }
}
