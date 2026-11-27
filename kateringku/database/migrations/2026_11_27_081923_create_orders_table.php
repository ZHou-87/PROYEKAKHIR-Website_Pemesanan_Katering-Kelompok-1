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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_code')->unique();
            $table->date('event_date');
            $table->enum('delivery_method', ['delivery', 'pickup']);
            $table->decimal('distance_km', 8, 2)->default(0);
            $table->decimal('delivery_fee', 12, 2)->default(0);
            $table->text('delivery_address');
            $table->string('phone', 30);
            $table->text('notes')->nullable();
            $table->decimal('total_price', 12, 2);
            $table->string('payment_method')->nullable();
            $table->timestamp('payment_expired_at')->nullable();
            $table->enum('order_status', [
                'pending',
                'waiting_validation',
                'processed',
                'completed',
                'cancelled',
            ])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
