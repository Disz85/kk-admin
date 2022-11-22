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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id');
            $table->string('title');
            $table->string('slug');
            $table->text('lead')->nullable();
            $table->text('body')->nullable();
            $table->foreignId('category_id')->constrained('categories')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('image_id')->nullable()->constrained('media')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('active')->default(false);
            $table->boolean('hidden')->default(false);
            $table->boolean('sponsored')->default(false);
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
        Schema::dropIfExists('articles');
    }
};
