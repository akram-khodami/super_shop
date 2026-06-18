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
        Schema::create('user_addresses', function (Blueprint $table) {
               $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            // منزل - محل کار - ...

            $table->string('recipient_name');

            $table->string('mobile', 20);

            $table->string('province');

            $table->string('city');

            $table->text('address');

            $table->string('postal_code', 20)
                ->nullable();

            $table->boolean('is_default')
                ->default(false);

            $table->timestamps();

            $table->index('user_id');
            $table->index('is_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
