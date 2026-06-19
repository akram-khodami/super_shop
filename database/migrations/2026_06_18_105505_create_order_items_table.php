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
        Schema::create('order_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('product_id')//for better report
            ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('variant_id')
                ->constrained()
                ->restrictOnDelete();

            // snapshot price (very important)
            $table->string('product_title');

            $table->string('variant_title')
                ->nullable();

            $table->decimal('unit_price', 12, 2);

            $table->unsignedInteger('quantity');

            $table->decimal('total_amount', 12, 2);//unit_price × quantity = total_amount

            $table->timestamps();

            $table->index(['order_id', 'product_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
