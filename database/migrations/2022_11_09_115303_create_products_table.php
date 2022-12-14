<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id');
            $table->string('legacy_image_url')->nullable();
            $table->longText('legacy_description')->nullable();
            $table->string('legacy_created_by')->nullable()->index();
            $table->string('legacy_updated_by')->nullable()->index();
            $table->string('name')->unique();
            $table->string('canonical_name')->nullable();
            $table->string('slug')->unique();
            $table->integer('image_id')->nullable()->constrained('media')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('price')->nullable();
            $table->string('size')->nullable();
            $table->text('where_to_find')->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('brand_id')->nullable()->constrained('brands')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('active')->default(false);
            $table->boolean('hidden')->default(false);
            $table->boolean('sponsored')->default(false);
            $table->boolean('is_18_plus')->default(false);
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
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
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
