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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->text('legacy_id')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->longText('description')->nullable();
            $table->foreignId('image_id')->nullable()->constrained('media')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('archived')->default(false);
            $table->string('type');
            $table->index('legacy_id', 'legacy_id_index');
            $table->index('type', 'type_index');
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
        Schema::dropIfExists('categories');
    }
};
