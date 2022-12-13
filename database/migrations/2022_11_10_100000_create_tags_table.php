<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('legacy_id')->nullable();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->foreignId('image_id')->nullable()->constrained('media')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
            $table->index('slug', 'slug_index');
            $table->index('legacy_id', 'legacy_id_index');
        });
    }

    public function down()
    {
        Schema::drop('tags');
    }
};
