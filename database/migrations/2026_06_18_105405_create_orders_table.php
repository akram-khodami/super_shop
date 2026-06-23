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

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_address_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('order_number')
                ->unique();

            $table->string('status')
                ->default('pending');

            $table->string('payment_status')
                ->default('unpaid')->comment('Order Payment Status');

            $table->decimal('subtotal', 12, 2)
                ->default(0);

            $table->decimal('shipping_amount', 12, 2)
                ->default(0);

            $table->decimal('discount_amount', 12, 2)
                ->default(0);

            $table->decimal('total_amount', 12, 2)
                ->default(0);//total_amount =subtotal+ shipping_amount- discount_amount

            //address snappshot
            $table->string('recipient_name');

            $table->string('mobile', 20);

            $table->string('province');

            $table->string('city');

            $table->text('address');

            $table->string('postal_code', 20)
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->timestamp('paid_at')
                ->nullable();

            $table->timestamp('processing_at')
                ->nullable();

            $table->timestamp('shipped_at')
                ->nullable();

            $table->timestamp('delivered_at')
                ->nullable();

            $table->timestamp('completed_at')
                ->nullable();

            $table->string('tracking_code')
                ->nullable();

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
