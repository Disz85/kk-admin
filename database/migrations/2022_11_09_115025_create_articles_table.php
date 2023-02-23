<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table): void {
            $table->id();
            $table->uuid()->default(DB::raw('UUID()'));
            $table->unsignedBigInteger('legacy_id')->nullable();
            $table->string('title');
            $table->string('legacy_slug')->nullable();
            $table->string('slug');
            $table->text('lead')->nullable();
            $table->longText('body')->nullable();
            $table->foreignId('image_id')->nullable()->constrained('media')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_sponsored')->default(false);
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
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
