<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('membership_reward_claims', function (Blueprint $table) {

            $table->timestamp('used_at')
                ->nullable()
                ->after('claimed_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('membership_reward_claims', function (Blueprint $table) {
            //
        });
    }
};
