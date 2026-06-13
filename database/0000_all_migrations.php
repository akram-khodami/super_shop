<?php
//
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
//
//return new class extends Migration
//{
//    /**
//     * Run the migrations.
//     */
//    public function up(): void
//    {
//        Schema::create('users', function (Blueprint $table) {
//            $table->id();
//            $table->string('name');
//            $table->string('email')->unique();
//            $table->timestamp('email_verified_at')->nullable();
//            $table->string('password');
//            $table->rememberToken();
//            $table->timestamps();
//        });
//
//        Schema::create('password_reset_tokens', function (Blueprint $table) {
//            $table->string('email')->primary();
//            $table->string('token');
//            $table->timestamp('created_at')->nullable();
//        });
//
//        Schema::create('sessions', function (Blueprint $table) {
//            $table->string('id')->primary();
//            $table->foreignId('user_id')->nullable()->index();
//            $table->string('ip_address', 45)->nullable();
//            $table->text('user_agent')->nullable();
//            $table->longText('payload');
//            $table->integer('last_activity')->index();
//        });
//    }
//
//    /**
//     * Reverse the migrations.
//     */
//    public function down(): void
//    {
//        Schema::dropIfExists('users');
//        Schema::dropIfExists('password_reset_tokens');
//        Schema::dropIfExists('sessions');
//    }
//};
//
//
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
//
//return new class extends Migration
//{
//    /**
//     * Run the migrations.
//     */
//    public function up(): void
//    {
//        Schema::create('cache', function (Blueprint $table) {
//            $table->string('key')->primary();
//            $table->mediumText('value');
//            $table->bigInteger('expiration')->index();
//        });
//
//        Schema::create('cache_locks', function (Blueprint $table) {
//            $table->string('key')->primary();
//            $table->string('owner');
//            $table->bigInteger('expiration')->index();
//        });
//    }
//
//    /**
//     * Reverse the migrations.
//     */
//    public function down(): void
//    {
//        Schema::dropIfExists('cache');
//        Schema::dropIfExists('cache_locks');
//    }
//};
//
//
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
//
//return new class extends Migration
//{
//    /**
//     * Run the migrations.
//     */
//    public function up(): void
//    {
//        Schema::create('jobs', function (Blueprint $table) {
//            $table->id();
//            $table->string('queue')->index();
//            $table->longText('payload');
//            $table->unsignedSmallInteger('attempts');
//            $table->unsignedInteger('reserved_at')->nullable();
//            $table->unsignedInteger('available_at');
//            $table->unsignedInteger('created_at');
//        });
//
//        Schema::create('job_batches', function (Blueprint $table) {
//            $table->string('id')->primary();
//            $table->string('name');
//            $table->integer('total_jobs');
//            $table->integer('pending_jobs');
//            $table->integer('failed_jobs');
//            $table->longText('failed_job_ids');
//            $table->mediumText('options')->nullable();
//            $table->integer('cancelled_at')->nullable();
//            $table->integer('created_at');
//            $table->integer('finished_at')->nullable();
//        });
//
//        Schema::create('failed_jobs', function (Blueprint $table) {
//            $table->id();
//            $table->string('uuid')->unique();
//            $table->string('connection');
//            $table->string('queue');
//            $table->longText('payload');
//            $table->longText('exception');
//            $table->timestamp('failed_at')->useCurrent();
//
//            $table->index(['connection', 'queue', 'failed_at']);
//        });
//    }
//
//    /**
//     * Reverse the migrations.
//     */
//    public function down(): void
//    {
//        Schema::dropIfExists('jobs');
//        Schema::dropIfExists('job_batches');
//        Schema::dropIfExists('failed_jobs');
//    }
//};
//
//
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
//
//return new class extends Migration
//{
//    /**
//     * Run the migrations.
//     */
//    public function up(): void
//    {
//        Schema::create('categories', function (Blueprint $table) {
//            $table->id();
//
//            $table->string('name');
//            $table->string('slug')->unique();
//
//            $table->text('description')->nullable();
//
//            $table->string('image')->nullable();
//
//            $table->boolean('is_active')->default(true);
//
//            $table->timestamps();
//
//            $table->softDeletes();
//
//        });
//    }
//
//    /**
//     * Reverse the migrations.
//     */
//    public function down(): void
//    {
//        Schema::dropIfExists('categories');
//    }
//};
//
//
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
//
//return new class extends Migration
//{
//    /**
//     * Run the migrations.
//     */
//    public function up(): void
//    {
//        Schema::create('brands', function (Blueprint $table) {
//            $table->id();
//
//            $table->string('name');
//            $table->string('slug')->unique();
//
//            $table->string('logo')->nullable();
//            $table->text('description')->nullable();
//
//            $table->boolean('is_active')->default(true);
//
//            $table->timestamps();
//
//            $table->softDeletes();
//
//        });
//    }
//
//    /**
//     * Reverse the migrations.
//     */
//    public function down(): void
//    {
//        Schema::dropIfExists('brands');
//    }
//};
//
//
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
//
//return new class extends Migration
//{
//    /**
//     * Run the migrations.
//     */
//    public function up(): void
//    {
//        Schema::create('products', function (Blueprint $table) {
//            $table->id();
//
//            $table->foreignId('category_id')
//                ->constrained();
//
//            $table->foreignId('brand_id')
//                ->nullable()
//                ->constrained()
//                ->nullOnDelete();
//
//            $table->string('name');
//
//            $table->string('slug')->unique();
//
//            $table->longText('description')->nullable();
//
//            $table->boolean('featured')->default(false);
//
//            $table->boolean('is_active')->default(true);
//
//            $table->timestamps();
//            $table->softDeletes();
//
//            $table->index('is_active');
//            $table->index('featured');
//            $table->index(['category_id', 'is_active']);
//
//        });
//    }
//
//    /**
//     * Reverse the migrations.
//     */
//    public function down(): void
//    {
//        Schema::dropIfExists('products');
//    }
//};
//
//
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
//
//return new class extends Migration
//{
//    /**
//     * Run the migrations.
//     */
//    public function up(): void
//    {
//        Schema::create('variants', function (Blueprint $table) {
//            $table->id();
//
//            $table->foreignId('product_id')
//                ->constrained();
//
//            $table->string('sku')->unique();
//
//            $table->string('barcode')
//                ->nullable();
//
//            $table->decimal(
//                'price',
//                12,
//                2
//            );
//
//            $table->decimal(
//                'sale_price',
//                12,
//                2
//            )->nullable();
//
//            $table->unsignedInteger('stock')
//                ->default(0);
//
//            $table->boolean('is_default')
//                ->default(false);
//
//            $table->boolean('is_active')
//                ->default(true);
//
//            $table->timestamps();
//
//            $table->index(['product_id', 'is_active']);
//            $table->index('stock');
//
//            $table->unsignedBigInteger('cost_price')->nullable();
//            $table->decimal('weight', 8, 2)->nullable();
//
//        });
//    }
//
//    /**
//     * Reverse the migrations.
//     */
//    public function down(): void
//    {
//        Schema::dropIfExists('variants');
//    }
//};
//
//
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
//
//return new class extends Migration
//{
//    /**
//     * Run the migrations.
//     */
//    public function up(): void
//    {
//        Schema::create('attributes', function (Blueprint $table) {
//            $table->id();
//            $table->string('name');
//            $table->string('slug')->unique();
//            $table->timestamps();
//            $table->string('type')->default('text');
//        });
//    }
//
//    /**
//     * Reverse the migrations.
//     */
//    public function down(): void
//    {
//        Schema::dropIfExists('attributes');
//    }
//};
//
//
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
//
//return new class extends Migration
//{
//    /**
//     * Run the migrations.
//     */
//    public function up(): void
//    {
//        Schema::create('product_images', function (Blueprint $table) {
//            $table->id();
//
//            $table->foreignId('product_id')
//                ->constrained()
//                ->cascadeOnDelete();
//
//            $table->string('image');
//
//            $table->unsignedInteger('sort_order')
//                ->default(0);
//
//            $table->string('alt_text')->nullable();
//            $table->boolean('is_primary')->default(false);
//
//            $table->timestamps();
//        });
//    }
//
//    /**
//     * Reverse the migrations.
//     */
//    public function down(): void
//    {
//        Schema::dropIfExists('product_images');
//    }
//};
//
//
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
//
//return new class extends Migration
//{
//    /**
//     * Run the migrations.
//     */
//    public function up(): void
//    {
//        Schema::create('product_variant_images', function (Blueprint $table) {
//            $table->id();
//            $table->foreignId('variant_id')->constrained()->cascadeOnDelete();
//            $table->string('image');
//            $table->string('alt_text')->nullable();
//            $table->integer('sort_order')->default(0);
//            $table->boolean('is_primary')->default(false);
//            $table->timestamps();
//            $table->index([
//                'variant_id',
//                'sort_order'
//            ]);
//        });
//    }
//
//    /**
//     * Reverse the migrations.
//     */
//    public function down(): void
//    {
//        Schema::dropIfExists('product_variant_images');
//    }
//};
//
//
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
//
//return new class extends Migration
//{
//    /**
//     * Run the migrations.
//     */
//    public function up(): void
//    {
//        Schema::create('stock_movements', function (Blueprint $table) {
//
//            $table->id();
//
//            $table->foreignId('variant_id')
//                ->constrained()
//                ->cascadeOnDelete();
//
//            $table->enum('type', [
//                'in',
//                'out',
//                'adjustment'
//            ]);
//
//            $table->integer('quantity');
//
//            $table->integer('before_stock');
//
//            $table->integer('after_stock');
//
//            $table->text('note')
//                ->nullable();
//
//            $table->foreignId('user_id')
//                ->nullable()
//                ->constrained()
//                ->nullOnDelete();
//
//            $table->timestamps();
//
//            $table->index('created_at');
//
//        });
//    }
//
//    /**
//     * Reverse the migrations.
//     */
//    public function down(): void
//    {
//        Schema::dropIfExists('stock_movements');
//    }
//};
//
//
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
//
//return new class extends Migration
//{
//    /**
//     * Run the migrations.
//     */
//    public function up(): void
//    {
//        Schema::create('product_attributes', function (Blueprint $table) {
//            $table->id();
//            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
//            $table->foreignId('attribute_id')->constrained()->cascadeOnDelete();
//            $table->boolean('is_variant')->default(false);
//            $table->timestamps();
//            $table->unique([
//                'product_id',
//                'attribute_id'
//            ]);
//        });
//    }
//
//    /**
//     * Reverse the migrations.
//     */
//    public function down(): void
//    {
//        Schema::dropIfExists('product_attributes');
//    }
//};
//
//
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
//
//return new class extends Migration
//{
//    /**
//     * Run the migrations.
//     */
//    public function up(): void
//    {
//        Schema::create('product_attribute_values', function (Blueprint $table) {
//            $table->id();
//            $table->foreignId('product_attribute_id')->constrained()->cascadeOnDelete();
//            $table->string('value');
//            $table->timestamps();
//            $table->unique([
//                'product_attribute_id',
//                'value'
//            ]);
//        });
//    }
//
//    /**
//     * Reverse the migrations.
//     */
//    public function down(): void
//    {
//        Schema::dropIfExists('product_attribute_values');
//    }
//};
//
//
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
//
//return new class extends Migration
//{
//    /**
//     * Run the migrations.
//     */
//    public function up(): void
//    {
//        Schema::create('variant_values', function (Blueprint $table) {
//            $table->id();
//            $table->foreignId('variant_id')->constrained()->cascadeOnDelete();
//            $table->foreignId('product_attribute_value_id')->constrained()->cascadeOnDelete();
//            $table->timestamps();
//            $table->unique([
//                'variant_id',
//                'product_attribute_value_id'
//            ]);
//        });
//    }
//
//    /**
//     * Reverse the migrations.
//     */
//    public function down(): void
//    {
//        Schema::dropIfExists('variant_values');
//    }
//};
