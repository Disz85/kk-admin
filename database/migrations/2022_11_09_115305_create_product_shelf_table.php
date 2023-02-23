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
    public function up(): void
    {
        Schema::create('product_shelf', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('shelf_id')->constrained('shelves')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
            $table->unique([ 'shelf_id', 'product_id' ], 'shelf_item');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('product_shelf');
    }
};
