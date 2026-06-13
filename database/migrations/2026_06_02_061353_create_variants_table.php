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
        Schema::create('variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained();

            $table->string('sku')->unique();

            $table->string('barcode')
                ->nullable();

            $table->decimal(
                'price',
                12,
                2
            );

            $table->decimal(
                'sale_price',
                12,
                2
            )->nullable();


            $table->unsignedInteger('stock')
                ->default(0);

            $table->boolean('is_default')
                ->default(false);

            $table->boolean('is_active')
                ->default(true);

            $table->unsignedBigInteger('cost_price')->nullable();
            $table->decimal('weight', 8, 2)->nullable();

            $table->timestamps();

            $table->index(['product_id', 'is_active']);
            $table->index('stock');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
