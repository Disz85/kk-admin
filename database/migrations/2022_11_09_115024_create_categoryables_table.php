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
        Schema::create('categoryables', function (Blueprint $table): void {
            $table->foreignId('category_id')->constrained('categories')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedBigInteger('categoryable_id');
            $table->string('categoryable_type');
            $table->unique([ 'category_id', 'categoryable_id', 'categoryable_type' ], 'unique_category');
            $table->index([ 'categoryable_id', 'categoryable_type' ]);
            $table->index('categoryable_id');
            $table->index('categoryable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('categoryables');
    }
};
