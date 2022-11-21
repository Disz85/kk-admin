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
        Schema::create('categoryables', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained('categories')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedBigInteger('categoryable_id');
            $table->string('categoryable_type');
            $table->string('type');
            $table->unique([ 'category_id', 'categoryable_id', 'categoryable_type', 'type' ], 'unique_category');
            $table->index([ 'categoryable_id', 'categoryable_type' ]);
            $table->index('type');
            $table->index('categoryable_id');
            $table->index('categoryable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categoryables');
    }
};
