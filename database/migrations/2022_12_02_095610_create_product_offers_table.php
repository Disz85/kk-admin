<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('product_offers', function (Blueprint $table): void {
            $table->id();
            $table->uuid()->default(DB::raw('UUID()'));
            $table->unsignedBigInteger('legacy_id')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->longText('legacy_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('price')->nullable();
            $table->string('used')->nullable();
            $table->string('place')->nullable();
            $table->string('shipping_payment')->nullable();
            $table->boolean('is_sold')->default(0);
            $table->dateTime('approved_at')->nullable();
            $table->unsignedBigInteger('legacy_product_id')->nullable();
            $table->foreignId('product_id')->nullable()->constrained('products')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('legacy_created_by')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('legacy_image_url')->nullable();
            $table->foreignId('image_id')->nullable()->constrained('media')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('legacy_bought_by')->nullable();
            $table->foreignId('bought_by')->nullable()->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->dateTime('bought_at')->nullable();
            $table->timestamps();
            $table->index('legacy_id', 'legacy_id_index');
            $table->index('legacy_product_id', 'legacy_product_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('product_offers');
    }
};
