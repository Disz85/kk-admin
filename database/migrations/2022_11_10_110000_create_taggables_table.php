<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('taggables', function (Blueprint $table): void {
            $table->foreignId('tag_id')->constrained('tags')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedBigInteger('taggable_id');
            $table->string('taggable_type');
            $table->unique([ 'tag_id', 'taggable_id', 'taggable_type'], 'unique_tag');
            $table->index([ 'taggable_id', 'taggable_type' ]);
            $table->index('taggable_id');
            $table->index('taggable_type');
        });
    }

    public function down(): void
    {
        Schema::drop('taggables');
    }
};
