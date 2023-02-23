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
        Schema::create('article_author', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('author_id')->constrained('authors')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->unique([ 'article_id', 'author_id' ], 'author');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('article_author');
    }
};
