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
        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->uuid()->default(DB::raw('UUID()'));
            $table->unsignedBigInteger('legacy_id')->nullable()->index();
            $table->string('legacy_image_url')->nullable();
            $table->longText('legacy_description')->nullable();
            $table->string('legacy_created_by')->nullable()->index();
            $table->string('legacy_updated_by')->nullable()->index();
            $table->string('name')->unique();
            $table->string('canonical_name')->nullable();
            $table->string('slug')->unique();
            $table->foreignId('image_id')->nullable()->constrained('media')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('price')->nullable();
            $table->string('size')->nullable();
            $table->text('where_to_find')->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('brand_id')->nullable()->constrained('brands')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('is_sponsored')->default(false);
            $table->boolean('is_18_plus')->default(false);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('ingredients_by')->nullable()->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('legacy_ingredients_by')->nullable()->index();
            $table->dateTime('published_at')->nullable();
            $table->index('legacy_id', 'legacy_id_index');
            $table->index('description', 'description_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
