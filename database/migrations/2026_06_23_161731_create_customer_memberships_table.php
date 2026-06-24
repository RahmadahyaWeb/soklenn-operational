<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_memberships', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('membership_number')->unique();

            $table->enum('tier', [
                'regular',
                'family',
            ])->default('regular');

            $table->unsignedInteger('stamp')->default(0);

            $table->unsignedInteger('total_orders')->default(0);

            $table->decimal('total_spent', 15, 2)->default(0);

            $table->timestamp('member_since')->nullable();

            $table->timestamp('family_since')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_memberships');
    }
};
