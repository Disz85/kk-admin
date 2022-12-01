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
            $table->string('name');
            $table->string('canonical_name')->nullable();
            $table->string('slug');
            $table->longText('description')->nullable();
            $table->foreignId('brand_id')->constrained('brands')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('active')->default(false);
            $table->boolean('hidden')->default(false);
            $table->boolean('sponsored')->default(false);
            $table->unsignedBigInteger('price')->nullable();
            $table->boolean('is_18_plus')->default(false);
            $table->timestamps();
            $table->dateTime('published_at')->nullable();
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
