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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->string('url')->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('image_id')->nullable()->constrained('media')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('where_to_find')->nullable();
            $table->dateTime('approved')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brands');
    }
};
