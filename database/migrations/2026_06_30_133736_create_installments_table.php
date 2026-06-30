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
        Schema::create('installments', function (Blueprint $table) {

            $table->id();

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            // Installment number (1,2,3,...)
            $table->unsignedTinyInteger('number');

            $table->decimal('amount', 12, 2);

            $table->string('status')
                ->default('pending');

            $table->timestamp('due_at');

            $table->timestamp('paid_at')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'order_id',
                'number'
            ]);

            $table->index([
                'order_id',
                'status'
            ]);

            $table->index('due_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
