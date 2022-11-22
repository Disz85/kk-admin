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
        Schema::create('shelves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->foreignId('user_id')->nullable()->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();

            $table->unique([ 'title', 'user_id' ]);
            $table->unique([ 'slug', 'user_id' ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wishlists');
    }
};
