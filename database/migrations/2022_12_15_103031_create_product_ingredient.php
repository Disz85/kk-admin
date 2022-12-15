<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_ingredient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained('ingredients')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->unique([ 'product_id', 'ingredient_id' ], 'product_ingredient_item');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_ingredient');
    }
};
