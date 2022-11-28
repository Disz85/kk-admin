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
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('name');
            $table->string('slug');
            $table->string('email');
            $table->foreignId('image_id')->nullable()->constrained('media')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->longText('description');
            $table->timestamps();
            $table->unique('email');
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authors');
    }
};
