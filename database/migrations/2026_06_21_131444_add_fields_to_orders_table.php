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
        Schema::table('orders', function (Blueprint $table) {
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
