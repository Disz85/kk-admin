<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::create('taggables', function (Blueprint $table) {
            $table->foreignId('tag_id')->constrained('tags')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedBigInteger('taggable_id');
            $table->string('taggable_type');
            $table->string('type');
            $table->unique([ 'tag_id', 'taggable_id', 'taggable_type', 'type' ], 'unique_tag');
            $table->index([ 'taggable_id', 'taggable_type' ]);
            $table->index('type');
            $table->index('taggable_id');
            $table->index('taggable_type');
        });
    }

    public function down()
    {
        Schema::drop('taggables');
    }
};
